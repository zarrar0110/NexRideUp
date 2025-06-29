<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'license_number',
        'experience_years',
        'phone',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Get the user that owns the driver profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the vehicles owned by the driver.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    /**
     * Get the trips for the driver.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    /**
     * Get the reviews for the driver.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'driver_id');
    }
}
