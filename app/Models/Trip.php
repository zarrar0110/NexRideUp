<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'request_id',
        'driver_id',
        'start_time',
        'end_time',
        'status',
        'fare',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the trip request that this trip fulfills.
     */
    public function tripRequest()
    {
        return $this->belongsTo(TripRequest::class, 'request_id');
    }

    /**
     * Get the driver for the trip.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Get the payment for the trip.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'trip_id');
    }

    /**
     * Get the reviews for the trip.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'trip_id');
    }
}
