<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">Edit Driver {{ $driver->name }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi pengemudi armada tambang</p>
        </div>
        <a href="{{ route('drivers.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">&larr; Kembali</a>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="POST" action="{{ route('drivers.update', $driver) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Driver *</label>
                    <input type="text" name="name" value="{{ old('name', $driver->name) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SIM *</label>
                        <input type="text" name="license_number" value="{{ old('license_number', $driver->license_number) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('license_number')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Masa Berlaku SIM *</label>
                        <input type="date" name="license_expiry" value="{{ old('license_expiry', $driver->license_expiry?->format('Y-m-d')) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('license_expiry')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / HP *</label>
                        <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Pool / Site *</label>
                        <select name="location_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach($locations as $l)
                                <option value="{{ $l->id }}" {{ old('location_id', $driver->location_id) == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('location_id')" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Ketersediaan *</label>
                        <select name="status" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="available" {{ old('status', $driver->status) === 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                            <option value="on_duty" {{ old('status', $driver->status) === 'on_duty' ? 'selected' : '' }}>On Duty (Bertugas)</option>
                            <option value="off" {{ old('status', $driver->status) === 'off' ? 'selected' : '' }}>Off (Cuti / Libur)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('drivers.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs">Update Driver</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
