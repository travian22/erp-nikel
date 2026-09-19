<x-app-layout>
    <!-- Page Header -->
    <div class="pb-4 border-b border-slate-200">
        <h1 class="text-xl font-bold text-slate-900 leading-tight">Daftar Persetujuan (Approvals)</h1>
        <p class="text-xs text-slate-500 mt-0.5">Tinjau dan lakukan persetujuan berjenjang pemesanan armada kendaraan tambang</p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Level Persetujuan</th>
                        <th class="py-3 px-4">Pegawai Pemohon</th>
                        <th class="py-3 px-4">Kendaraan</th>
                        <th class="py-3 px-4">Driver</th>
                        <th class="py-3 px-4">Tujuan / Keperluan</th>
                        <th class="py-3 px-4">Status Approval</th>
                        <th class="py-3 px-4 text-center">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700 bg-white">
                    @forelse($approvals as $app)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <a href="{{ route('bookings.show', $app->booking) }}" class="text-slate-900 hover:underline">
                                    {{ $app->booking?->booking_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-300">
                                    Level {{ $app->approval_level }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">{{ $app->booking?->requester?->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-900 block">{{ $app->booking?->vehicle?->brand }} {{ $app->booking?->vehicle?->model }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $app->booking?->vehicle?->plate_number }}</span>
                            </td>
                            <td class="py-3.5 px-4">{{ $app->booking?->driver?->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-900 block">{{ $app->booking?->destination }}</span>
                                <span class="text-[11px] text-slate-500">{{ $app->booking?->purpose }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="badge-status badge-{{ $app->status }}">
                                    {{ $app->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($app->status === 'menunggu')
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Approve Form -->
                                        <form id="approve-form-{{ $app->id }}" method="POST" action="{{ route('approvals.approve', $app) }}" class="inline">
                                            @csrf
                                            <button type="button" 
                                                    onclick="confirmAction(document.getElementById('approve-form-{{ $app->id }}'), {
                                                        title: 'Setujui Pemesanan Kendaraan',
                                                        message: 'Apakah Anda yakin ingin menyetujui pemesanan {{ $app->booking?->booking_code }} (Level {{ $app->approval_level }})?',
                                                        type: 'success',
                                                        icon: 'bi-check-circle-fill',
                                                        confirmText: 'Ya, Setujui',
                                                        cancelText: 'Batal'
                                                    })"
                                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-md shadow-2xs transition">
                                                <i class="bi bi-check-lg text-xs me-1"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form id="reject-form-{{ $app->id }}" method="POST" action="{{ route('approvals.reject', $app) }}" class="inline">
                                            @csrf
                                            <button type="button" 
                                                    onclick="confirmAction(document.getElementById('reject-form-{{ $app->id }}'), {
                                                        title: 'Tolak Pemesanan {{ $app->booking?->booking_code }}',
                                                        message: 'Silakan berikan alasan penolakan permohonan pemesanan kendaraan ini.',
                                                        type: 'danger',
                                                        icon: 'bi-x-circle-fill',
                                                        confirmText: 'Kirim Penolakan',
                                                        cancelText: 'Batal',
                                                        requiresInput: true,
                                                        inputLabel: 'Alasan Penolakan *',
                                                        inputPlaceholder: 'Berikan alasan penolakan...',
                                                        inputName: 'notes'
                                                    })"
                                                    class="inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-md shadow-2xs transition">
                                                <i class="bi bi-x-lg text-xs me-1"></i>
                                                <span>Tolak</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Selesai Diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-500">Tidak ada daftar persetujuan yang membutuhkan tindakan Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($approvals->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $approvals->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
