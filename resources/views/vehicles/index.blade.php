<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Master Data Armada Kendaraan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data kendaraan tambang (milik sendiri & sewa), kategori, dan lokasi pool</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0">
            <i class="bi bi-plus-lg text-xs"></i>
            <span>Tambah Kendaraan Baru</span>
        </a>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Plat Nomor</th>
                        <th class="py-3 px-4">Brand & Model</th>
                        <th class="py-3 px-4">Tahun</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Kepemilikan</th>
                        <th class="py-3 px-4">Lokasi Pool</th>
                        <th class="py-3 px-4">Status Armada</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                    @forelse($vehicles as $v)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $v->plate_number }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $v->brand }} {{ $v->model }}</td>
                            <td class="py-3.5 px-4">{{ $v->year }}</td>
                            <td class="py-3.5 px-4">{{ $v->category?->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $v->ownership_type === 'milik_sendiri' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-800' }}">
                                    {{ str_replace('_', ' ', $v->ownership_type) }}
                                </span>
                                @if($v->rentalCompany)
                                    <span class="block text-[10px] text-slate-500 mt-0.5">{{ $v->rentalCompany->name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">{{ $v->location?->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="badge-status {{ $v->status === 'tersedia' ? 'badge-disetujui' : ($v->status === 'digunakan' ? 'badge-selesai' : 'badge-ditolak') }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('vehicles.edit', $v) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                        <i class="bi bi-pencil-square text-xs"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form id="delete-vehicle-{{ $v->id }}" method="POST" action="{{ route('vehicles.destroy', $v) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmAction(document.getElementById('delete-vehicle-{{ $v->id }}'), {
                                                    title: 'Hapus Armada Kendaraan',
                                                    message: 'Apakah Anda yakin ingin menghapus data kendaraan {{ $v->brand }} {{ $v->model }} ({{ $v->plate_number }})? Tindakan ini tidak dapat dibatalkan.',
                                                    type: 'danger',
                                                    icon: 'bi-trash-fill',
                                                    confirmText: 'Ya, Hapus Kendaraan',
                                                    cancelText: 'Batal'
                                                })"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition space-x-1">
                                            <i class="bi bi-trash text-xs"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-500">Belum ada data kendaraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
