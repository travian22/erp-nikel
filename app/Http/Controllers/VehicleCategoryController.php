<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLog;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleCategory::withCount('vehicles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return redirect()->route('categories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories,name',
            'description' => 'nullable|string',
        ]);

        $category = VehicleCategory::create($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menambahkan kategori kendaraan baru: {$category->name}",
            'module' => 'VehicleCategory',
            'reference_table' => 'vehicle_categories',
            'reference_id' => $category->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori kendaraan berhasil ditambahkan.');
    }

    public function edit(VehicleCategory $category)
    {
        return redirect()->route('categories.index');
    }

    public function update(Request $request, VehicleCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Memperbarui kategori kendaraan: {$category->name}",
            'module' => 'VehicleCategory',
            'reference_table' => 'vehicle_categories',
            'reference_id' => $category->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Data kategori kendaraan berhasil diperbarui.');
    }

    public function destroy(Request $request, VehicleCategory $category)
    {
        if ($category->vehicles()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori kendaraan tidak dapat dihapus karena masih terhubung dengan unit armada.');
        }

        $id = $category->id;
        $name = $category->name;
        $category->delete();

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus kategori kendaraan: {$name}",
            'module' => 'VehicleCategory',
            'reference_table' => 'vehicle_categories',
            'reference_id' => $id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori kendaraan berhasil dihapus.');
    }
}
