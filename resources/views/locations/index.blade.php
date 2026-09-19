<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Master Lokasi & Site Tambang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola daftar lokasi pool kendaraan, kantor pusat, dan area pertambangan</p>
        </div>
        <button type="button" 
                x-data="" 
                x-on:click.prevent="$dispatch('open-modal', 'create-location-modal')" 
                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0 cursor-pointer">
            <i class="bi bi-plus-lg text-xs"></i>
            <span>Tambah Lokasi Baru</span>
        </button>
    </div>



    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('locations.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lokasi, tipe, alamat..." class="text-xs rounded-md border-slate-300 w-72 focus:border-slate-500 focus:ring-slate-500">
                <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white text-xs font-semibold rounded-md hover:bg-slate-900 transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('locations.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Lokasi / Site</th>
                            <th class="py-3 px-4">Tipe Lokasi</th>
                            <th class="py-3 px-4">Alamat / Keterangan</th>
                            <th class="py-3 px-4">Armada</th>
                            <th class="py-3 px-4">Driver</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($locations as $loc)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $loc->name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ str_replace('_', ' ', $loc->type) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">{{ $loc->address ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $loc->vehicles_count }} Unit
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $loc->drivers_count }} Personel
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <button type="button" 
                                                x-data="" 
                                                x-on:click.prevent="$dispatch('open-modal', 'edit-location-{{ $loc->id }}')" 
                                                class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                            <i class="bi bi-pencil-square text-xs"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form id="delete-loc-{{ $loc->id }}" action="{{ route('locations.destroy', $loc) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmAction(document.getElementById('delete-loc-{{ $loc->id }}'), {
                                                        title: 'Hapus Master Lokasi',
                                                        message: 'Apakah Anda yakin ingin menghapus lokasi {{ addslashes($loc->name) }}?',
                                                        type: 'danger',
                                                        confirmText: 'Ya, Hapus Lokasi'
                                                    })" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition space-x-1">
                                                <i class="bi bi-trash text-xs"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal for {{ $loc->name }} -->
                                    <x-form-modal name="edit-location-{{ $loc->id }}" title="Edit Data Lokasi" subtitle="Perbarui nama, tipe, atau alamat lokasi" icon="bi-pencil-square" :show="old('location_id') == $loc->id">
                                        <form method="POST" action="{{ route('locations.update', $loc) }}" class="p-5 space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="location_id" value="{{ $loc->id }}">

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lokasi / Site *</label>
                                                <input type="text" name="name" value="{{ old('location_id') == $loc->id ? old('name') : $loc->name }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                @if(old('location_id') == $loc->id)
                                                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Lokasi *</label>
                                                <select name="type" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                    @php $currentType = old('location_id') == $loc->id ? old('type') : $loc->type; @endphp
                                                    <option value="kantor_pusat" {{ $currentType === 'kantor_pusat' ? 'selected' : '' }}>Kantor Pusat (Head Office)</option>
                                                    <option value="kantor_cabang" {{ $currentType === 'kantor_cabang' ? 'selected' : '' }}>Kantor Cabang / Pool Office</option>
                                                    <option value="tambang" {{ $currentType === 'tambang' ? 'selected' : '' }}>Site Pertambangan</option>
                                                </select>
                                                @if(old('location_id') == $loc->id)
                                                    @error('type') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat / Keterangan Lokasi</label>
                                                <textarea name="address" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">{{ old('location_id') == $loc->id ? old('address') : $loc->address }}</textarea>
                                            </div>

                                            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </x-form-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">Belum ada data lokasi master.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <x-form-modal name="create-location-modal" title="Tambah Master Lokasi Baru" subtitle="Input data lokasi pool, kantor, atau area pertambangan" icon="bi-plus-lg" :show="$errors->any() && !old('location_id')">
        <form method="POST" action="{{ route('locations.store') }}" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lokasi / Site *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: Pool Utama Head Office / Site Morowali">
                @if(!old('location_id'))
                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Lokasi *</label>
                <select name="type" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                    <option value="">-- Pilih Tipe Lokasi --</option>
                    <option value="kantor_pusat" {{ old('type') === 'kantor_pusat' ? 'selected' : '' }}>Kantor Pusat (Head Office)</option>
                    <option value="kantor_cabang" {{ old('type') === 'kantor_cabang' ? 'selected' : '' }}>Kantor Cabang / Pool Office</option>
                    <option value="tambang" {{ old('type') === 'tambang' ? 'selected' : '' }}>Site Pertambangan</option>
                </select>
                @if(!old('location_id'))
                    @error('type') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat / Keterangan Lokasi</label>
                <textarea name="address" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Alamat fisik atau area lokasi...">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs">Simpan Lokasi</button>
            </div>
        </form>
    </x-form-modal>
</x-app-layout>
