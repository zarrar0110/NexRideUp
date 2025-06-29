<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripRequest extends Model
{
    protected $fillable = [
        'passenger_id',
        'pickup_location',
        'dropoff_location',
        'status',
        'request_time',
        'seats',
        'estimated_fare',
        'payment_method',
        'notes',
    ];

    /**
     * Get the passenger that owns the trip request.
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class, 'passenger_id');
    }

    /**
     * Get the trip associated with the trip request.
     */
    public function trip()
    {
        return $this->hasOne(Trip::class, 'request_id');
    }
}
