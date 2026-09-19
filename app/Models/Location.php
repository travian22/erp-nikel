<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'location_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'location_id');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'location_id');
    }
}
