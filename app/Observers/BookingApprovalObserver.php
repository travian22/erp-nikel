<?php

namespace App\Observers;

use App\Models\BookingApproval;
use App\Services\ActivityLogger;

class BookingApprovalObserver
{
    public function updated(BookingApproval $approval): void
    {
        if ($approval->isDirty('status') && $approval->status !== 'menunggu') {
            $bookingCode = $approval->booking?->booking_code ?? "#{$approval->booking_id}";
            $actionStr = $approval->status === 'disetujui' ? 'Menyetujui' : 'Menolak';

            ActivityLogger::log(
                activity: "{$actionStr} Pemesanan {$bookingCode} (Level {$approval->approval_level})",
                module: 'Persetujuan',
                referenceTable: 'booking_approvals',
                referenceId: $approval->id,
                userId: $approval->approver_id
            );
        }
    }
}
