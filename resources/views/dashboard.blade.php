<x-app-layout>
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Dashboard Monitoring Armada Tambang</h1>
            <p class="text-xs text-slate-500 mt-1">Overview real-time armada kendaraan, lokasi pool, & status persetujuan 2-level</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded shadow-2xs transition space-x-2 shrink-0">
                <i class="bi bi-plus-lg text-xs"></i>
                <span>Buat Pemesanan</span>
            </a>
        @endif
    </div>

    <div class="space-y-6">
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: Total Armada -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs flex items-start justify-between">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Armada</span>
                    <div class="text-2xl font-bold text-slate-900">
                        {{ $totalVehicles }} <span class="text-xs font-normal text-slate-500">Unit</span>
                    </div>
                    <span class="text-xs text-slate-500 block pt-1">Miliki & Sewa Tambang</span>
                </div>
                <div class="p-3 rounded bg-slate-100 text-slate-700 border border-slate-200">
                    <i class="bi bi-car-front text-xl"></i>
                </div>
            </div>

            <!-- Card 2: Armada Tersedia -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs flex items-start justify-between">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Armada Tersedia</span>
                    <div class="text-2xl font-bold text-emerald-700">
                        {{ $availableVehicles }} <span class="text-xs font-normal text-slate-500">Unit</span>
                    </div>
                    <span class="text-xs text-emerald-700 font-medium block pt-1">Siap Bertugas di Pool</span>
                </div>
                <div class="p-3 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="bi bi-check-circle text-xl"></i>
                </div>
            </div>

            <!-- Card 3: Sedang Digunakan -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs flex items-start justify-between">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Sedang Digunakan</span>
                    <div class="text-2xl font-bold text-slate-800">
                        {{ $usedVehicles }} <span class="text-xs font-normal text-slate-500">Unit</span>
                    </div>
                    <span class="text-xs text-slate-600 block pt-1">Aktif Operasional Field</span>
                </div>
                <div class="p-3 rounded bg-slate-100 text-slate-700 border border-slate-200">
                    <i class="bi bi-truck-front text-xl"></i>
                </div>
            </div>

            <!-- Card 4: Pending Approval -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-2xs flex items-start justify-between">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Menunggu Persetujuan</span>
                    <div class="text-2xl font-bold text-amber-700">
                        {{ $pendingApprovals }} <span class="text-xs font-normal text-slate-500">Booking</span>
                    </div>
                    <span class="text-xs text-amber-700 font-medium block pt-1">Pending L1 / L2 Approval</span>
                </div>
                <div class="p-3 rounded bg-amber-50 text-amber-700 border border-amber-200">
                    <i class="bi bi-hourglass-split text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ApexCharts Section 1: Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Chart 1: Pemakaian Per Tambang (7 Cols) -->
            <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-slate-200 shadow-2xs">
                <div class="pb-3.5 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-sm text-slate-900">Pemakaian Kendaraan Per Lokasi Tambang</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Frekuensi pemesanan berdasarkan site operasional</p>
                </div>
                <div id="chartLocation" class="w-full h-72"></div>
            </div>

            <!-- Chart 2: Donut Status Booking (5 Cols) -->
            <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-slate-200 shadow-2xs">
                <div class="pb-3.5 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-sm text-slate-900">Proporsi Status Booking</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Distribusi persetujuan 2-level saat ini</p>
                </div>
                <div id="chartStatus" class="w-full h-72 flex items-center justify-center"></div>
            </div>
        </div>

        <!-- ApexCharts Section 2: Secondary Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Chart 3: Konsumsi BBM (7 Cols) -->
            <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-slate-200 shadow-2xs">
                <div class="pb-3.5 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-sm text-slate-900">Tren Pengisian Bahan Bakar (BBM)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Konsumsi BBM (Liter) per Bulan</p>
                </div>
                <div id="chartFuel" class="w-full h-64"></div>
            </div>

            <!-- Chart 4: Kategori Kendaraan (5 Cols) -->
            <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-slate-200 shadow-2xs">
                <div class="pb-3.5 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-sm text-slate-900">Kategori Armada Kendaraan</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Angkutan Orang vs Angkutan Barang</p>
                </div>
                <div id="chartCategory" class="w-full h-64 flex items-center justify-center"></div>
            </div>
        </div>

        <!-- Clean ERP Data Table: Pemesanan Terbaru -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Pemesanan Kendaraan Terbaru</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Status persetujuan berjenjang terkini</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
                    Lihat Semua <i class="bi bi-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Kode Booking</th>
                            <th class="py-3.5 px-6">Pemohon</th>
                            <th class="py-3.5 px-6">Kendaraan</th>
                            <th class="py-3.5 px-6">Tujuan</th>
                            <th class="py-3.5 px-6">Status Pemesanan</th>
                            <th class="py-3.5 px-6">Approval Level 1</th>
                            <th class="py-3.5 px-6">Approval Level 2</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                        @forelse($recentBookings as $b)
                            @php
                                $l1 = $b->approvals->where('approval_level', 1)->first();
                                $l2 = $b->approvals->where('approval_level', 2)->first();
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <a href="{{ route('bookings.show', $b) }}" class="text-slate-900 hover:underline">
                                        {{ $b->booking_code }}
                                    </a>
                                </td>
                                <td class="py-4 px-6">{{ $b->requester?->name }}</td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-900 block">{{ $b->vehicle?->brand }} {{ $b->vehicle?->model }}</span>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $b->vehicle?->plate_number }}</span>
                                </td>
                                <td class="py-4 px-6">{{ $b->destination }}</td>
                                <td class="py-4 px-6">
                                    <span class="badge-status badge-{{ $b->status }}">
                                        {{ str_replace('_', ' ', $b->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[11px] font-semibold {{ $l1?->status === 'disetujui' ? 'text-emerald-700' : ($l1?->status === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                                        {{ $l1?->approver?->name }}: {{ strtoupper($l1?->status ?? 'menunggu') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[11px] font-semibold {{ $l2?->status === 'disetujui' ? 'text-emerald-700' : ($l2?->status === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                                        {{ $l2?->approver?->name }}: {{ strtoupper($l2?->status ?? 'menunggu') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-500">Belum ada data pemesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ApexCharts Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Bar Chart Pemakaian Per Tambang
            var locOptions = {
                chart: { type: 'bar', height: 280, toolbar: { show: false } },
                series: [{ name: 'Jumlah Pemesanan', data: @json($chartLocationCounts) }],
                xaxis: { 
                    categories: @json($chartLocationNames), 
                    labels: { 
                        rotate: -20,
                        style: { fontFamily: 'Plus Jakarta Sans', fontSize: '11px', colors: '#64748B' } 
                    } 
                },
                colors: ['#059669'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '35%' } },
                dataLabels: { enabled: false },
                grid: { borderColor: '#E2E8F0', strokeDashArray: 3 }
            };
            new ApexCharts(document.querySelector("#chartLocation"), locOptions).render();

            // Chart 2: Donut Status Pemesanan
            var statusOptions = {
                chart: { type: 'donut', height: 280 },
                series: [{{ $statusCounts['Menunggu'] }}, {{ $statusCounts['Disetujui'] }}, {{ $statusCounts['Ditolak'] }}, {{ $statusCounts['Selesai'] }}],
                labels: ['Menunggu L1/L2', 'Disetujui', 'Ditolak', 'Selesai'],
                colors: ['#D97706', '#059669', '#DC2626', '#4F46E5'],
                legend: { position: 'bottom', fontFamily: 'Plus Jakarta Sans', fontSize: '11px' },
                dataLabels: { enabled: true }
            };
            new ApexCharts(document.querySelector("#chartStatus"), statusOptions).render();

            // Chart 3: Area Chart BBM
            var fuelOptions = {
                chart: { type: 'area', height: 240, toolbar: { show: false } },
                series: [{ name: 'BBM (Liter)', data: @json($chartFuelLiters) }],
                xaxis: { categories: @json($chartFuelMonths), labels: { style: { fontFamily: 'Plus Jakarta Sans', fontSize: '11px' } } },
                colors: ['#2563EB'],
                fill: { type: 'solid', opacity: 0.1 },
                stroke: { curve: 'straight', width: 2 },
                grid: { borderColor: '#E2E8F0', strokeDashArray: 3 }
            };
            new ApexCharts(document.querySelector("#chartFuel"), fuelOptions).render();

            // Chart 4: Pie Chart Kategori Kendaraan
            var catOptions = {
                chart: { type: 'pie', height: 240 },
                series: @json($chartCategoryCounts),
                labels: @json($chartCategoryNames),
                colors: ['#059669', '#D97706'],
                legend: { position: 'bottom', fontFamily: 'Plus Jakarta Sans', fontSize: '11px' }
            };
            new ApexCharts(document.querySelector("#chartCategory"), catOptions).render();
        });
    </script>
</x-app-layout>
