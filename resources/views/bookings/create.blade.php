<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">Form Pemesanan Kendaraan Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Input data pemesanan, penugasan driver, dan pihak penanggung jawab persetujuan 2-level</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">&larr; Kembali</a>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pegawai Pemohon -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pegawai Pemohon *</label>
                        <select name="requester_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('requester_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->position }} - {{ $emp->department }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('requester_id')" class="mt-1" />
                    </div>

                    <!-- Pilih Kendaraan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Armada Kendaraan (Tersedia) *</label>
                        <select name="vehicle_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>{{ $v->brand }} {{ $v->model }} [{{ $v->plate_number }}] - Pool: {{ $v->location?->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vehicle_id')" class="mt-1" />
                    </div>

                    <!-- Driver -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Driver Ditugaskan *</label>
                        <select name="driver_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Pilih Driver --</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->name }} (SIM: {{ $d->license_number }}) - Pool: {{ $d->location?->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('driver_id')" class="mt-1" />
                    </div>

                    <!-- Jumlah Penumpang / Muatan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Penumpang / Muatan *</label>
                        <input type="number" name="passenger_or_load_qty" value="{{ old('passenger_or_load_qty', 1) }}" min="1" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 4 (Orang) / 1000 (Kg)">
                        <x-input-error :messages="$errors->get('passenger_or_load_qty')" class="mt-1" />
                    </div>

                    <!-- Purpose -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Keperluan Pemesanan *</label>
                        <input type="text" name="purpose" value="{{ old('purpose') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Inspeksi Lapangan PIT 3 Pomalaa">
                        <x-input-error :messages="$errors->get('purpose')" class="mt-1" />
                    </div>

                    <!-- Destination -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Tujuan *</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Area Tambang PIT 3 Pomalaa">
                        <x-input-error :messages="$errors->get('destination')" class="mt-1" />
                    </div>

                    <!-- Dates -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Mulai Pemakaian *</label>
                        <input type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('start_datetime')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Selesai Pemakaian *</label>
                        <input type="datetime-local" name="end_datetime" value="{{ old('end_datetime') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        <x-input-error :messages="$errors->get('end_datetime')" class="mt-1" />
                    </div>
                </div>

                <!-- Section Approval Berjenjang 2-Level -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                    <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Penetapan Pihak Penyetuju (Approval Berjenjang 2 Level)</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Approver Level 1 (Manager/Atasan Langsung) *</label>
                            <select name="approver_level_1_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">-- Pilih Approver Level 1 --</option>
                                @foreach($approvers as $app)
                                    <option value="{{ $app->id }}" {{ old('approver_level_1_id') == $app->id ? 'selected' : '' }}>{{ $app->name }} ({{ $app->username }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('approver_level_1_id')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Approver Level 2 (GM/Direksi Ops) *</label>
                            <select name="approver_level_2_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">-- Pilih Approver Level 2 --</option>
                                @foreach($approvers as $app)
                                    <option value="{{ $app->id }}" {{ old('approver_level_2_id') == $app->id ? 'selected' : '' }}>{{ $app->name }} ({{ $app->username }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('approver_level_2_id')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs">
                        Kirim Pengajuan Pemesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
