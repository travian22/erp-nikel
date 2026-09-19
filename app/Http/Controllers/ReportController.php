<?php

namespace App\Http\Controllers;

use App\Exports\BookingReportExport;
use App\Models\Booking;
use App\Models\Location;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $locations = Location::all();

        $query = Booking::with(['requester', 'vehicle.location', 'driver', 'approvals.approver'])
            ->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_datetime', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location_id')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('reports.index', compact('locations', 'bookings'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $locationId = $request->input('location_id');

        ActivityLogger::log('Mengekspor Laporan Pemesanan Kendaraan ke Excel', 'Laporan', 'bookings');

        $fileName = 'laporan-pemesanan-kendaraan-'.date('Ymd-His').'.xlsx';

        return Excel::download(
            new BookingReportExport($startDate, $endDate, $status, $locationId),
            $fileName
        );
    }
}
