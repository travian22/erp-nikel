<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Daftar Pemesanan Kendaraan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh riwayat & status persetujuan pemesanan kendaraan tambang</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1.5 shrink-0">
                <i class="bi bi-plus-lg text-xs"></i>
                <span>Tambah Pemesanan Baru</span>
            </a>
        @endif
    </div>

    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('bookings.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, tujuan, keperluan..." class="text-xs rounded-md border-slate-300 w-64 focus:border-slate-500 focus:ring-slate-500">
                <select name="status" class="text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-2xs transition flex items-center space-x-1">
                    <i class="bi bi-funnel text-xs"></i>
                    <span>Filter</span>
                </button>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4 whitespace-nowrap">Kode</th>
                            <th class="py-3 px-4 whitespace-nowrap">Pemohon</th>
                            <th class="py-3 px-4 whitespace-nowrap">Kendaraan</th>
                            <th class="py-3 px-4 whitespace-nowrap">Driver</th>
                            <th class="py-3 px-4 whitespace-nowrap">Tujuan</th>
                            <th class="py-3 px-4 whitespace-nowrap">Status Pemesanan</th>
                            <th class="py-3 px-4 whitespace-nowrap">Approval Level 1</th>
                            <th class="py-3 px-4 whitespace-nowrap">Approval Level 2</th>
                            <th class="py-3 px-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($bookings as $b)
                            @php
                                $l1 = $b->approvals->where('approval_level', 1)->first();
                                $l2 = $b->approvals->where('approval_level', 2)->first();
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">{{ $b->booking_code }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $b->requester?->name }}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-900 whitespace-nowrap">{{ $b->vehicle?->brand }} {{ $b->vehicle?->model }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $b->driver?->name }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $b->destination }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="badge-status badge-{{ $b->status }}">
                                        {{ str_replace('_', ' ', $b->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-[11px] font-semibold {{ $l1?->status === 'disetujui' ? 'text-emerald-700' : ($l1?->status === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                                        {{ $l1?->approver?->name }}: {{ strtoupper($l1?->status ?? '-') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="text-[11px] font-semibold {{ $l2?->status === 'disetujui' ? 'text-emerald-700' : ($l2?->status === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                                        {{ $l2?->approver?->name }}: {{ strtoupper($l2?->status ?? '-') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('bookings.show', $b) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-md border border-slate-300 transition space-x-1">
                                            <i class="bi bi-info-circle text-xs"></i>
                                            <span>Detail</span>
                                        </a>
                                        @if(Auth::user()->isAdmin() && $b->status === 'disetujui')
                                            <a href="{{ route('bookings.complete', $b) }}" 
                                               class="inline-flex items-center px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-md shadow-2xs transition space-x-1">
                                                <i class="bi bi-check2-circle text-xs"></i>
                                                <span>Selesaikan</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-slate-500">Belum ada pemesanan kendaraan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
