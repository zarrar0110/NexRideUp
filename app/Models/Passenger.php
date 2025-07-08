<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    /**
     * Get the user that owns the passenger profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Get the trip requests for the passenger.
     */
    public function tripRequests()
    {
        return $this->hasMany(TripRequest::class, 'passenger_id');
    }

    /**
     * Get the trips for the passenger through trip requests.
     */
    public function trips()
    {
        return $this->hasManyThrough(Trip::class, TripRequest::class, 'passenger_id', 'request_id', 'id', 'id');
    }

    /**
     * Get the reviews written by the passenger.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'passenger_id');
    }

    protected $fillable = [
        'id',
    ];

    protected $table = 'passengers';
}
