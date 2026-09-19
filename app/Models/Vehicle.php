<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year',
        'category_id',
        'ownership_type',
        'rental_company_id',
        'location_id',
        'status',
        'capacity',
        'fuel_type',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function rentalCompany(): BelongsTo
    {
        return $this->belongsTo(RentalCompany::class, 'rental_company_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'vehicle_id');
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class, 'vehicle_id');
    }

    public function serviceSchedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class, 'vehicle_id');
    }
}
