<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'approval_level',
        'approver_id',
        'status',
        'notes',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approval_level' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
