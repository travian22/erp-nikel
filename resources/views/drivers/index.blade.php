<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Master Data Driver Tambang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data pengemudi operasional & lisensi SIM resmi</p>
        </div>
        <a href="{{ route('drivers.create') }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0">
            <i class="bi bi-plus-lg text-xs"></i>
            <span>Tambah Driver Baru</span>
        </a>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Nama Driver</th>
                        <th class="py-3 px-4">Nomor SIM</th>
                        <th class="py-3 px-4">Masa Berlaku SIM</th>
                        <th class="py-3 px-4">No. HP / Telepon</th>
                        <th class="py-3 px-4">Lokasi Pool</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                    @forelse($drivers as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $d->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-800">{{ $d->license_number }}</td>
                            <td class="py-3.5 px-4">{{ $d->license_expiry?->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4">{{ $d->phone }}</td>
                            <td class="py-3.5 px-4">{{ $d->location?->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $d->status === 'available' ? 'bg-emerald-100 text-emerald-800' : ($d->status === 'on_duty' ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ str_replace('_', ' ', $d->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('drivers.edit', $d) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-md border border-amber-300 transition space-x-1">
                                        <i class="bi bi-pencil-square text-xs"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form id="delete-driver-{{ $d->id }}" method="POST" action="{{ route('drivers.destroy', $d) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmAction(document.getElementById('delete-driver-{{ $d->id }}'), {
                                                    title: 'Hapus Data Driver Tambang',
                                                    message: 'Apakah Anda yakin ingin menghapus data driver {{ $d->name }} (SIM: {{ $d->license_number }})? Tindakan ini tidak dapat dibatalkan.',
                                                    type: 'danger',
                                                    icon: 'bi-person-x-fill',
                                                    confirmText: 'Ya, Hapus Driver',
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
                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada data driver.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($drivers->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $drivers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
