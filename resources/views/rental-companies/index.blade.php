<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Master Perusahaan Rental</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data mitra perusahaan sewa/rental kendaraan tambang</p>
        </div>
        <button type="button" 
                x-data="" 
                x-on:click.prevent="$dispatch('open-modal', 'create-rental-modal')" 
                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0 cursor-pointer">
            <i class="bi bi-plus-lg text-xs"></i>
            <span>Tambah Perusahaan Rental</span>
        </button>
    </div>



    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('rental-companies.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, contact person, telepon..." class="text-xs rounded-md border-slate-300 w-72 focus:border-slate-500 focus:ring-slate-500">
                <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white text-xs font-semibold rounded-md hover:bg-slate-900 transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('rental-companies.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Perusahaan</th>
                            <th class="py-3 px-4">Contact Person</th>
                            <th class="py-3 px-4">Telepon / HP</th>
                            <th class="py-3 px-4">Alamat Mitra</th>
                            <th class="py-3 px-4">Unit Terhubung</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($rentals as $rental)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $rental->name }}</td>
                                <td class="py-3.5 px-4">{{ $rental->contact_person ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-mono">{{ $rental->phone ?? '-' }}</td>
                                <td class="py-3.5 px-4">{{ $rental->address ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $rental->vehicles_count }} Unit
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <button type="button" 
                                                x-data="" 
                                                x-on:click.prevent="$dispatch('open-modal', 'edit-rental-{{ $rental->id }}')" 
                                                class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                            <i class="bi bi-pencil-square text-xs"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form id="delete-rental-{{ $rental->id }}" action="{{ route('rental-companies.destroy', $rental) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmAction(document.getElementById('delete-rental-{{ $rental->id }}'), {
                                                        title: 'Hapus Perusahaan Rental',
                                                        message: 'Apakah Anda yakin ingin menghapus perusahaan rental {{ addslashes($rental->name) }}?',
                                                        type: 'danger',
                                                        confirmText: 'Ya, Hapus Mitra'
                                                    })" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition space-x-1">
                                                <i class="bi bi-trash text-xs"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal for {{ $rental->name }} -->
                                    <x-form-modal name="edit-rental-{{ $rental->id }}" title="Edit Perusahaan Rental" subtitle="Perbarui informasi mitra rental" icon="bi-pencil-square" :show="old('rental_id') == $rental->id">
                                        <form method="POST" action="{{ route('rental-companies.update', $rental) }}" class="p-5 space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan Rental *</label>
                                                <input type="text" name="name" value="{{ old('rental_id') == $rental->id ? old('name') : $rental->name }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                @if(old('rental_id') == $rental->id)
                                                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Person (PIC)</label>
                                                    <input type="text" name="contact_person" value="{{ old('rental_id') == $rental->id ? old('contact_person') : $rental->contact_person }}" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">No. Telepon / HP</label>
                                                    <input type="text" name="phone" value="{{ old('rental_id') == $rental->id ? old('phone') : $rental->phone }}" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Kantor / Depot</label>
                                                <textarea name="address" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">{{ old('rental_id') == $rental->id ? old('address') : $rental->address }}</textarea>
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
                                <td colspan="6" class="py-6 text-center text-slate-500">Belum ada data perusahaan rental.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rentals->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $rentals->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <x-form-modal name="create-rental-modal" title="Tambah Perusahaan Rental Baru" subtitle="Input data mitra penyedia sewa kendaraan" icon="bi-plus-lg" :show="$errors->any() && !old('rental_id')">
        <form method="POST" action="{{ route('rental-companies.store') }}" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan Rental *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: PT Trans Borneo Rental">
                @if(!old('rental_id'))
                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Person (PIC)</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: Budi Santoso">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">No. Telepon / HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: 081234567890">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Kantor / Depot</label>
                <textarea name="address" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Alamat lengkap perusahaan rental...">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs">Simpan Perusahaan Rental</button>
            </div>
        </form>
    </x-form-modal>
</x-app-layout>
