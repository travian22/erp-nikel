<?php

use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Support\Facades\DB;

$booking = Booking::with('approvals')->find(18);

if ($booking) {
    echo "Found booking: " . $booking->booking_code . " Initial Status: " . $booking->status . "\n";
    
    DB::transaction(function () use ($booking) {
        // Level 1 approval
        $app1 = $booking->approvals()->where('approval_level', 1)->first();
        if ($app1) {
            $app1->update([
                'status' => 'disetujui',
                'notes' => 'Disetujui oleh Atasan Level 1 - Head of Mining Region 1',
                'approved_at' => now(),
            ]);
            echo "Level 1 approved.\n";
        }

        // Level 2 approval
        $app2 = $booking->approvals()->where('approval_level', 2)->first();
        if ($app2) {
            $app2->update([
                'status' => 'disetujui',
                'notes' => 'Disetujui oleh Atasan Level 2 - VP Operations',
                'approved_at' => now(),
            ]);
            echo "Level 2 approved.\n";
        }

        $booking->update(['status' => 'disetujui']);
        $booking->vehicle->update(['status' => 'digunakan']);
        $booking->driver->update(['status' => 'on_duty']);
    });

    echo "Booking " . $booking->booking_code . " is now fully DISETUJUI!\n";
}
