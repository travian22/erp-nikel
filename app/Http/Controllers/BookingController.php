<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\FuelLog;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUsageHistory;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['requester', 'vehicle', 'driver', 'creator', 'approvals.approver'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $employees = Employee::all();
        $vehicles = Vehicle::where('status', 'tersedia')->get();
        $drivers = Driver::where('status', 'available')->get();
        $approvers = User::where('role', 'approver')->where('is_active', true)->get();

        return view('bookings.create', compact('employees', 'vehicles', 'drivers', 'approvers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'requester_id' => 'required|exists:employees,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'purpose' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_datetime' => 'required|date|after_or_equal:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'passenger_or_load_qty' => 'required|integer|min:1',
            'approver_level_1_id' => 'required|exists:users,id',
            'approver_level_2_id' => 'required|exists:users,id|different:approver_level_1_id',
        ]);

        DB::transaction(function () use ($validated) {
            $bookingCode = 'BOOK-'.Carbon::now()->format('Ym').'-'.str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'requester_id' => $validated['requester_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'driver_id' => $validated['driver_id'],
                'purpose' => $validated['purpose'],
                'destination' => $validated['destination'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'passenger_or_load_qty' => $validated['passenger_or_load_qty'],
                'status' => 'menunggu_persetujuan',
                'created_by' => Auth::id(),
            ]);

            // Persetujuan Berjenjang (Level 1)
            BookingApproval::create([
                'booking_id' => $booking->id,
                'approval_level' => 1,
                'approver_id' => $validated['approver_level_1_id'],
                'status' => 'menunggu',
            ]);

            // Persetujuan Berjenjang (Level 2)
            BookingApproval::create([
                'booking_id' => $booking->id,
                'approval_level' => 2,
                'approver_id' => $validated['approver_level_2_id'],
                'status' => 'menunggu',
            ]);
        });

        return redirect()->route('bookings.index')->with('success', 'Pemesanan kendaraan berhasil diajukan dan menunggu persetujuan 2-level.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['requester', 'vehicle', 'driver', 'creator', 'approvals.approver', 'usageHistory', 'fuelLogs']);

        return view('bookings.show', compact('booking'));
    }

    public function completeForm(Booking $booking): View|RedirectResponse
    {
        if ($booking->status !== 'disetujui') {
            return redirect()->route('bookings.show', $booking)->with('error', 'Hanya pemesanan yang telah disetujui yang dapat diselesaikan.');
        }

        return view('bookings.complete', compact('booking'));
    }

    public function storeComplete(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'disetujui') {
            return redirect()->route('bookings.show', $booking)->with('error', 'Status pemesanan tidak valid.');
        }

        $validated = $request->validate([
            'start_odometer' => 'required|integer|min:0',
            'end_odometer' => 'required|integer|gte:start_odometer',
            'actual_start_time' => 'required|date',
            'actual_end_time' => 'required|date|after_or_equal:actual_start_time',
            'fuel_liters' => 'nullable|numeric|min:0',
            'fuel_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $booking) {
            VehicleUsageHistory::create([
                'booking_id' => $booking->id,
                'start_odometer' => $validated['start_odometer'],
                'end_odometer' => $validated['end_odometer'],
                'actual_start_time' => $validated['actual_start_time'],
                'actual_end_time' => $validated['actual_end_time'],
                'notes' => $validated['notes'] ?? null,
            ]);

            if (! empty($validated['fuel_liters']) && $validated['fuel_liters'] > 0) {
                FuelLog::create([
                    'vehicle_id' => $booking->vehicle_id,
                    'booking_id' => $booking->id,
                    'fuel_date' => Carbon::parse($validated['actual_end_time'])->format('Y-m-d'),
                    'liters' => $validated['fuel_liters'],
                    'cost' => $validated['fuel_cost'] ?? 0,
                    'odometer' => $validated['end_odometer'],
                ]);
            }

            $booking->update(['status' => 'selesai']);

            // Release vehicle & driver
            $booking->vehicle->update(['status' => 'tersedia']);
            $booking->driver->update(['status' => 'available']);
        });

        ActivityLogger::log("Menyelesaikan pemakaian kendaraan untuk pemesanan {$booking->booking_code}", 'Pemakaian Kendaraan', 'bookings', $booking->id);

        return redirect()->route('bookings.show', $booking)->with('success', 'Pemakaian kendaraan telah dicatat dan status pemesanan telah selesai.');
    }
}
