<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">Detail Pemesanan {{ $booking->booking_code }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Rincian pemesanan, riwayat persetujuan 2-level, dan penggunaan kendaraan</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">&larr; Kembali</a>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Info (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Pemesanan</span>
                    <span class="badge-status badge-{{ $booking->status }}">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block">Pegawai Pemohon</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $booking->requester?->name }}</span>
                        <span class="text-slate-500 block">{{ $booking->requester?->position }} ({{ $booking->requester?->department }})</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Di-input oleh (Admin)</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $booking->creator?->name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Armada Kendaraan</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }}</span>
                        <span class="text-slate-500 block font-mono">Plat: {{ $booking->vehicle?->plate_number }} (Pool: {{ $booking->vehicle?->location?->name }})</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Driver Ditugaskan</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $booking->driver?->name }}</span>
                        <span class="text-slate-500 block">SIM: {{ $booking->driver?->license_number }}</span>
                    </div>

                    <div class="col-span-2">
                        <span class="text-slate-400 font-medium block">Keperluan Pemesanan</span>
                        <span class="font-bold text-slate-900">{{ $booking->purpose }}</span>
                    </div>

                    <div class="col-span-2">
                        <span class="text-slate-400 font-medium block">Lokasi Tujuan</span>
                        <span class="font-bold text-slate-900">{{ $booking->destination }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Waktu Mulai</span>
                        <span class="font-bold text-slate-900">{{ $booking->start_datetime?->format('d F Y, H:i') }} WITA</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Waktu Selesai</span>
                        <span class="font-bold text-slate-900">{{ $booking->end_datetime?->format('d F Y, H:i') }} WITA</span>
                    </div>
                </div>
            </div>

            <!-- Vehicle Usage History (if completed) -->
            @if($booking->usageHistory)
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                    <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <span>Riwayat Pemakaian Aktual</span>
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-slate-400 block">Odometer Awal</span>
                            <span class="font-bold text-slate-900 text-sm">{{ number_format($booking->usageHistory->start_odometer) }} KM</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Odometer Akhir</span>
                            <span class="font-bold text-slate-900 text-sm">{{ number_format($booking->usageHistory->end_odometer) }} KM</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Total Jarak Tempuh</span>
                            <span class="font-bold text-emerald-600 text-sm">{{ number_format($booking->usageHistory->end_odometer - $booking->usageHistory->start_odometer) }} KM</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Waktu Mulai Aktual</span>
                            <span class="font-bold text-slate-900">{{ $booking->usageHistory->actual_start_time?->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Approval Timeline (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Timeline Persetujuan 2-Level</span>
                </h3>

                <div class="space-y-4">
                    @foreach($booking->approvals as $app)
                        <div class="p-4 rounded-xl border {{ $app->status === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : ($app->status === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 'border-amber-200 bg-amber-50/50') }}">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-xs text-slate-900">Level {{ $app->approval_level }} Approval</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $app->status === 'disetujui' ? 'bg-emerald-100 text-emerald-700' : ($app->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $app->status }}
                                </span>
                            </div>

                            <div class="mt-2 text-xs space-y-1">
                                <p class="text-slate-700 font-semibold">Penyetuju: {{ $app->approver?->name }}</p>
                                @if($app->approved_at)
                                    <p class="text-slate-400 text-[11px]">Waktu: {{ $app->approved_at->format('d/m/Y H:i') }}</p>
                                @endif
                                @if($app->notes)
                                    <p class="text-slate-600 italic bg-white/80 p-2 rounded-lg text-[11px] mt-2 border border-slate-200/50">
                                        "{{ $app->notes }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
