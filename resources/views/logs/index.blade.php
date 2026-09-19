<x-app-layout>
    <!-- Page Header -->
    <div class="pb-4 border-b border-slate-200">
        <h1 class="text-xl font-bold text-slate-900 leading-tight">Audit Trail / Log Aktivitas Aplikasi</h1>
        <p class="text-xs text-slate-500 mt-0.5">Catatan riwayat aktivitas pengguna (input pemesanan, approval, reject, dll)</p>
    </div>

    <div class="space-y-5">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-2xs">
            <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas log..." class="text-xs rounded border-slate-300 w-64 focus:border-slate-500 focus:ring-slate-500">
                <select name="module" class="text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua Modul</option>
                    <option value="Pemesanan" {{ request('module') === 'Pemesanan' ? 'selected' : '' }}>Pemesanan</option>
                    <option value="Persetujuan" {{ request('module') === 'Persetujuan' ? 'selected' : '' }}>Persetujuan</option>
                    <option value="Master Kendaraan" {{ request('module') === 'Master Kendaraan' ? 'selected' : '' }}>Master Kendaraan</option>
                    <option value="Master Driver" {{ request('module') === 'Master Driver' ? 'selected' : '' }}>Master Driver</option>
                    <option value="Laporan" {{ request('module') === 'Laporan' ? 'selected' : '' }}>Laporan</option>
                </select>
                <button type="submit" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded shadow-2xs">Filter</button>
            </form>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Pengguna (User)</th>
                            <th class="py-3 px-4">Modul</th>
                            <th class="py-3 px-4">Aktivitas</th>
                            <th class="py-3 px-4">Ref Table</th>
                            <th class="py-3 px-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $log->user?->name ?? $log->user?->username ?? 'Sistem' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-300">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $log->activity }}</td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $log->reference_table }} #{{ $log->reference_id }}</td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $log->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">Belum ada log aktivitas terdeteksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
