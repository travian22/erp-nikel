<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleUsageHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_usage_history';

    protected $fillable = [
        'booking_id',
        'start_odometer',
        'end_odometer',
        'actual_start_time',
        'actual_end_time',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'actual_start_time' => 'datetime',
            'actual_end_time' => 'datetime',
            'start_odometer' => 'integer',
            'end_odometer' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
