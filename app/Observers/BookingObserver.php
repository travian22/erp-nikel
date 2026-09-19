<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\ActivityLogger;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        ActivityLogger::log(
            activity: "Membuat pemesanan baru: {$booking->booking_code}",
            module: 'Pemesanan',
            referenceTable: 'bookings',
            referenceId: $booking->id
        );
    }

    public function updated(Booking $booking): void
    {
        if ($booking->isDirty('status')) {
            ActivityLogger::log(
                activity: "Mengubah status pemesanan {$booking->booking_code} menjadi: {$booking->status}",
                module: 'Pemesanan',
                referenceTable: 'bookings',
                referenceId: $booking->id
            );
        }
    }
}
