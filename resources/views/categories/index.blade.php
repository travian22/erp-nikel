<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Master Kategori Kendaraan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola klasifikasi tipe kendaraan (Angkutan Orang, Transportasi Material, Alat Berat, dll)</p>
        </div>
        <button type="button" 
                x-data="" 
                x-on:click.prevent="$dispatch('open-modal', 'create-category-modal')" 
                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0 cursor-pointer">
            <i class="bi bi-plus-lg text-xs"></i>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>



    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('categories.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori & deskripsi..." class="text-xs rounded-md border-slate-300 w-72 focus:border-slate-500 focus:ring-slate-500">
                <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white text-xs font-semibold rounded-md hover:bg-slate-900 transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="px-3 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Kategori</th>
                            <th class="py-3 px-4">Deskripsi / Peruntukan</th>
                            <th class="py-3 px-4">Jumlah Armada</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $cat->name }}</td>
                                <td class="py-3.5 px-4">{{ $cat->description ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $cat->vehicles_count }} Unit
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <button type="button" 
                                                x-data="" 
                                                x-on:click.prevent="$dispatch('open-modal', 'edit-category-{{ $cat->id }}')" 
                                                class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                            <i class="bi bi-pencil-square text-xs"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form id="delete-cat-{{ $cat->id }}" action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmAction(document.getElementById('delete-cat-{{ $cat->id }}'), {
                                                        title: 'Hapus Kategori Kendaraan',
                                                        message: 'Apakah Anda yakin ingin menghapus kategori {{ addslashes($cat->name) }}?',
                                                        type: 'danger',
                                                        confirmText: 'Ya, Hapus Kategori'
                                                    })" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition space-x-1">
                                                <i class="bi bi-trash text-xs"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal for {{ $cat->name }} -->
                                    <x-form-modal name="edit-category-{{ $cat->id }}" title="Edit Kategori Kendaraan" subtitle="Perbarui nama atau deskripsi klasifikasi kendaraan" icon="bi-pencil-square" :show="old('category_id') == $cat->id">
                                        <form method="POST" action="{{ route('categories.update', $cat) }}" class="p-5 space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="category_id" value="{{ $cat->id }}">

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori *</label>
                                                <input type="text" name="name" value="{{ old('category_id') == $cat->id ? old('name') : $cat->name }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                                                @if(old('category_id') == $cat->id)
                                                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                                                @endif
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Peruntukan Kategori</label>
                                                <textarea name="description" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">{{ old('category_id') == $cat->id ? old('description') : $cat->description }}</textarea>
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
                                <td colspan="4" class="py-6 text-center text-slate-500">Belum ada data kategori kendaraan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <x-form-modal name="create-category-modal" title="Tambah Kategori Kendaraan Baru" subtitle="Input klasifikasi tipe armada operasional" icon="bi-plus-lg" :show="$errors->any() && !old('category_id')">
        <form method="POST" action="{{ route('categories.store') }}" class="p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Contoh: Angkutan Orang / Transportasi Barang">
                @if(!old('category_id'))
                    @error('name') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Peruntukan Kategori</label>
                <textarea name="description" rows="3" class="w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Peruntukan tipe armada ini...">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded shadow-2xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs">Simpan Kategori</button>
            </div>
        </form>
    </x-form-modal>
</x-app-layout>
