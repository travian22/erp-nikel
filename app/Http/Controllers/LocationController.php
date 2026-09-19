<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLog;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::withCount(['vehicles', 'drivers']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $locations = $query->latest()->paginate(10)->withQueryString();

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return redirect()->route('locations.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'type' => 'required|in:kantor_pusat,kantor_cabang,tambang',
            'address' => 'nullable|string',
        ]);

        $location = Location::create($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menambahkan lokasi baru: {$location->name} ({$location->type})",
            'module' => 'Location',
            'reference_table' => 'locations',
            'reference_id' => $location->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('locations.index')->with('success', 'Master lokasi berhasil ditambahkan.');
    }

    public function edit(Location $location)
    {
        return redirect()->route('locations.index');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,'.$location->id,
            'type' => 'required|in:kantor_pusat,kantor_cabang,tambang',
            'address' => 'nullable|string',
        ]);

        $location->update($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Memperbarui data lokasi: {$location->name}",
            'module' => 'Location',
            'reference_table' => 'locations',
            'reference_id' => $location->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('locations.index')->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy(Request $request, Location $location)
    {
        if ($location->vehicles()->count() > 0 || $location->drivers()->count() > 0) {
            return redirect()->back()->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan oleh kendaraan atau driver.');
        }

        $id = $location->id;
        $name = $location->name;
        $location->delete();

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus lokasi: {$name}",
            'module' => 'Location',
            'reference_table' => 'locations',
            'reference_id' => $id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('locations.index')->with('success', 'Master lokasi berhasil dihapus.');
    }
}
