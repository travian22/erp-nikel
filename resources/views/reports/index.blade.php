<x-app-layout>
    <!-- Page Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Laporan Periodik Pemesanan Kendaraan</h1>
            <p class="text-xs text-slate-500 mt-1">Filter data pemesanan dan ekspor langsung ke format Microsoft Excel (.xlsx)</p>
        </div>
        <a href="{{ route('reports.export', request()->all()) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded shadow-2xs transition space-x-2 shrink-0">
            <i class="bi bi-file-earmark-excel text-sm"></i>
            <span>Export Laporan ke Excel (.xlsx)</span>
        </a>
    </div>

    <div class="space-y-6">
        <!-- Filter Card -->
        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs">
            <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider mb-4">Filter Laporan Periodik</h3>
            <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 items-end gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full h-10 px-3 text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full h-10 px-3 text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Pemesanan</label>
                    <select name="status" class="w-full h-10 px-3 text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lokasi Site / Pool</label>
                    <select name="location_id" class="w-full h-10 px-3 text-xs rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full h-10 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs flex items-center justify-center space-x-2 transition">
                        <i class="bi bi-search text-xs"></i>
                        <span>Tampilkan Data</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Preview Data Laporan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3.5 px-6 whitespace-nowrap">Kode Booking</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Pemohon</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Lokasi Pool</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Kendaraan</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Waktu Mulai</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Waktu Selesai</th>
                            <th class="py-3.5 px-6 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($bookings as $b)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-900 whitespace-nowrap">{{ $b->booking_code }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $b->requester?->name }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $b->vehicle?->location?->name }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $b->vehicle?->brand }} {{ $b->vehicle?->model }} ({{ $b->vehicle?->plate_number }})</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $b->start_datetime?->format('d/m/Y H:i') }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $b->end_datetime?->format('d/m/Y H:i') }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="badge-status badge-{{ $b->status }}">
                                        {{ str_replace('_', ' ', $b->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-500">Tidak ada data yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
