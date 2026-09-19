<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\RentalCompany;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with(['category', 'rentalCompany', 'location'])
            ->latest()
            ->paginate(10);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        $categories = VehicleCategory::all();
        $rentals = RentalCompany::all();
        $locations = Location::all();

        return view('vehicles.create', compact('categories', 'rentals', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:15|unique:vehicles,plate_number',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:'.(date('Y') + 1),
            'category_id' => 'required|exists:vehicle_categories,id',
            'ownership_type' => 'required|in:milik_sendiri,sewa',
            'rental_company_id' => 'nullable|required_if:ownership_type,sewa|exists:rental_companies,id',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:tersedia,digunakan,maintenance',
            'capacity' => 'required|integer|min:1',
            'fuel_type' => 'required|string|max:20',
        ]);

        $vehicle = Vehicle::create($validated);

        ActivityLogger::log("Menambahkan unit kendaraan baru: {$vehicle->plate_number} ({$vehicle->brand} {$vehicle->model})", 'Master Kendaraan', 'vehicles', $vehicle->id);

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $categories = VehicleCategory::all();
        $rentals = RentalCompany::all();
        $locations = Location::all();

        return view('vehicles.edit', compact('vehicle', 'categories', 'rentals', 'locations'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:15|unique:vehicles,plate_number,'.$vehicle->id,
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:'.(date('Y') + 1),
            'category_id' => 'required|exists:vehicle_categories,id',
            'ownership_type' => 'required|in:milik_sendiri,sewa',
            'rental_company_id' => 'nullable|required_if:ownership_type,sewa|exists:rental_companies,id',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:tersedia,digunakan,maintenance',
            'capacity' => 'required|integer|min:1',
            'fuel_type' => 'required|string|max:20',
        ]);

        $vehicle->update($validated);

        ActivityLogger::log("Perbarui data kendaraan: {$vehicle->plate_number}", 'Master Kendaraan', 'vehicles', $vehicle->id);

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $plate = $vehicle->plate_number;
        $id = $vehicle->id;
        $vehicle->delete();

        ActivityLogger::log("Menghapus data kendaraan: {$plate}", 'Master Kendaraan', 'vehicles', $id);

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
