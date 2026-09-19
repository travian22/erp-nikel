<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">Edit Kendaraan {{ $vehicle->plate_number }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi kendaraan armada tambang</p>
        </div>
        <a href="{{ route('vehicles.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">&larr; Kembali</a>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Plat Nomor *</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('plate_number')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Brand/Merk *</label>
                        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('brand')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Model / Tipe *</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('model')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Pembuatan *</label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('year')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Kendaraan *</label>
                        <select name="category_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id', $vehicle->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kepemilikan *</label>
                        <select name="ownership_type" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="milik_sendiri" {{ old('ownership_type', $vehicle->ownership_type) === 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                            <option value="sewa" {{ old('ownership_type', $vehicle->ownership_type) === 'sewa' ? 'selected' : '' }}>Sewa (Perusahaan Rental)</option>
                        </select>
                        <x-input-error :messages="$errors->get('ownership_type')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Perusahaan Rental</label>
                        <select name="rental_company_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih Perusahaan Rental --</option>
                            @foreach($rentals as $r)
                                <option value="{{ $r->id }}" {{ old('rental_company_id', $vehicle->rental_company_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('rental_company_id')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Pool / Site *</label>
                        <select name="location_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach($locations as $l)
                                <option value="{{ $l->id }}" {{ old('location_id', $vehicle->location_id) == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('location_id')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('capacity')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Bahan Bakar *</label>
                        <input type="text" name="fuel_type" value="{{ old('fuel_type', $vehicle->fuel_type) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('fuel_type')" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Armada *</label>
                        <select name="status" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="tersedia" {{ old('status', $vehicle->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="digunakan" {{ old('status', $vehicle->status) === 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                            <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs">Update Kendaraan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
