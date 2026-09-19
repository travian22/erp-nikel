<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Location;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::with('location')->latest()->paginate(10);

        return view('drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        $locations = Location::all();

        return view('drivers.create', compact('locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'license_number' => 'required|string|max:30',
            'license_expiry' => 'required|date',
            'phone' => 'required|string|max:20',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:available,on_duty,off',
        ]);

        $driver = Driver::create($validated);

        ActivityLogger::log("Menambahkan data driver baru: {$driver->name}", 'Master Driver', 'drivers', $driver->id);

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    public function edit(Driver $driver): View
    {
        $locations = Location::all();

        return view('drivers.edit', compact('driver', 'locations'));
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'license_number' => 'required|string|max:30',
            'license_expiry' => 'required|date',
            'phone' => 'required|string|max:20',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:available,on_duty,off',
        ]);

        $driver->update($validated);

        ActivityLogger::log("Perbarui data driver: {$driver->name}", 'Master Driver', 'drivers', $driver->id);

        return redirect()->route('drivers.index')->with('success', 'Data driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $name = $driver->name;
        $id = $driver->id;
        $driver->delete();

        ActivityLogger::log("Menghapus data driver: {$name}", 'Master Driver', 'drivers', $id);

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil dihapus.');
    }
}
