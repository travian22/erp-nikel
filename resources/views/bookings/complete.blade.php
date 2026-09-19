<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight">Penyelesaian Pemakaian {{ $booking->booking_code }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Input data odometer akhir, jam pemakaian aktual, dan log pengisian BBM</p>
        </div>
        <a href="{{ route('bookings.show', $booking) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">&larr; Kembali</a>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="POST" action="{{ route('bookings.store-complete', $booking) }}" class="space-y-6">
                @csrf

                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <p class="text-xs text-emerald-800 font-semibold">
                        Kendaraan: {{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }} [{{ $booking->vehicle?->plate_number }}] | Driver: {{ $booking->driver?->name }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Odometer Awal (KM) *</label>
                        <input type="number" name="start_odometer" value="{{ old('start_odometer', 10000) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Odometer Akhir (KM) *</label>
                        <input type="number" name="end_odometer" value="{{ old('end_odometer', 10250) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Mulai Aktual *</label>
                        <input type="datetime-local" name="actual_start_time" value="{{ old('actual_start_time', $booking->start_datetime?->format('Y-m-d\TH:i')) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Selesai Aktual *</label>
                        <input type="datetime-local" name="actual_end_time" value="{{ old('actual_end_time', $booking->end_datetime?->format('Y-m-d\TH:i')) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                    <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Pencatatan Konsumsi BBM (Opsional)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah BBM (Liter)</label>
                            <input type="number" step="0.01" name="fuel_liters" value="{{ old('fuel_liters') }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 45.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Total Biaya BBM (IDR)</label>
                            <input type="number" step="0.01" name="fuel_cost" value="{{ old('fuel_cost') }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 675000">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Kondisi fisik kendaraan, perjalanan, dll..."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('bookings.show', $booking) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">Batal</a>
                    <button type="button" 
                        onclick="confirmAction(this.form, { 
                            title: 'Selesaikan Pemakaian Kendaraan', 
                            message: 'Apakah Anda yakin data odometer dan pemakaian kendaraan sudah sesuai?', 
                            type: 'info', 
                            confirmText: 'Ya, Selesaikan Pemakaian' 
                        })"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs">
                        Simpan & Selesaikan Pemakaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
