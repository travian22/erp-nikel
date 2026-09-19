<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FuelLog;
use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. KPI Aggregates
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'tersedia')->count();
        $usedVehicles = Vehicle::where('status', 'digunakan')->count();
        $maintenanceVehicles = Vehicle::where('status', 'maintenance')->count();

        $totalBookingsThisMonth = Booking::whereMonth('created_at', now()->month)->count();
        $pendingApprovals = Booking::where('status', 'menunggu_persetujuan')->count();

        // 2. Chart 1: Pemakaian Kendaraan per Lokasi Tambang (ApexCharts Bar Chart)
        $locationsData = Location::withCount('vehicles')
            ->get()
            ->map(function ($loc) {
                return [
                    'name' => $loc->name,
                    'count' => Booking::whereHas('vehicle', fn ($q) => $q->where('location_id', $loc->id))->count(),
                ];
            });

        $chartLocationNames = $locationsData->pluck('name')->toArray();
        $chartLocationCounts = $locationsData->pluck('count')->toArray();

        // 3. Chart 2: Status Persetujuan Pemesanan (ApexCharts Donut Chart)
        $statusCounts = [
            'Menunggu' => Booking::where('status', 'menunggu_persetujuan')->count(),
            'Disetujui' => Booking::where('status', 'disetujui')->count(),
            'Ditolak' => Booking::where('status', 'ditolak')->count(),
            'Selesai' => Booking::where('status', 'selesai')->count(),
        ];

        // 4. Chart 3: Konsumsi BBM (ApexCharts Line/Area Chart)
        $fuelLogsData = FuelLog::select(
            DB::raw("DATE_TRUNC('month', fuel_date) as month"),
            DB::raw('SUM(liters) as total_liters'),
            DB::raw('SUM(cost) as total_cost')
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        $chartFuelMonths = $fuelLogsData->map(fn ($item) => date('M Y', strtotime($item->month)))->toArray();
        $chartFuelLiters = $fuelLogsData->pluck('total_liters')->map(fn ($val) => (float) $val)->toArray();
        $chartFuelCosts = $fuelLogsData->pluck('total_cost')->map(fn ($val) => (float) $val)->toArray();

        // Fallback sample data if fuel logs empty
        if (empty($chartFuelMonths)) {
            $chartFuelMonths = ['May', 'Jun', 'Jul', 'Aug', 'Sep'];
            $chartFuelLiters = [120, 150, 180, 210, 115.5];
            $chartFuelCosts = [1800000, 2250000, 2700000, 3150000, 1720000];
        }

        // 5. Chart 4: Kategori Kendaraan (Pie/Radial Chart)
        $categoryData = VehicleCategory::withCount('vehicles')->get();
        $chartCategoryNames = $categoryData->pluck('name')->toArray();
        $chartCategoryCounts = $categoryData->pluck('vehicles_count')->toArray();

        // 6. Recent Bookings for Dashboard Widget
        $recentBookings = Booking::with(['requester', 'vehicle', 'driver', 'approvals.approver'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'usedVehicles',
            'maintenanceVehicles',
            'totalBookingsThisMonth',
            'pendingApprovals',
            'chartLocationNames',
            'chartLocationCounts',
            'statusCounts',
            'chartFuelMonths',
            'chartFuelLiters',
            'chartFuelCosts',
            'chartCategoryNames',
            'chartCategoryCounts',
            'recentBookings'
        ));
    }
}
