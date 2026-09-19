<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLog;
use App\Models\RentalCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = RentalCompany::withCount('vehicles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $rentals = $query->latest()->paginate(10)->withQueryString();

        return view('rental-companies.index', compact('rentals'));
    }

    public function create()
    {
        return redirect()->route('rental-companies.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rental_companies,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $rental = RentalCompany::create($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menambahkan perusahaan rental baru: {$rental->name}",
            'module' => 'RentalCompany',
            'reference_table' => 'rental_companies',
            'reference_id' => $rental->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('rental-companies.index')->with('success', 'Perusahaan rental berhasil ditambahkan.');
    }

    public function edit(RentalCompany $rentalCompany)
    {
        return redirect()->route('rental-companies.index');
    }

    public function update(Request $request, RentalCompany $rentalCompany)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rental_companies,name,'.$rentalCompany->id,
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $rentalCompany->update($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Memperbarui data perusahaan rental: {$rentalCompany->name}",
            'module' => 'RentalCompany',
            'reference_table' => 'rental_companies',
            'reference_id' => $rentalCompany->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('rental-companies.index')->with('success', 'Data perusahaan rental berhasil diperbarui.');
    }

    public function destroy(Request $request, RentalCompany $rentalCompany)
    {
        if ($rentalCompany->vehicles()->count() > 0) {
            return redirect()->back()->with('error', 'Perusahaan rental tidak dapat dihapus karena masih terhubung dengan unit armada kendaraan.');
        }

        $id = $rentalCompany->id;
        $name = $rentalCompany->name;
        $rentalCompany->delete();

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus perusahaan rental: {$name}",
            'module' => 'RentalCompany',
            'reference_table' => 'rental_companies',
            'reference_id' => $id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('rental-companies.index')->with('success', 'Perusahaan rental berhasil dihapus.');
    }
}
