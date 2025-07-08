<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'trip_id',
        'passenger_id',
        'driver_id',
        'rating',
        'comment',
    ];

    protected $table = 'reviews';

    /**
     * Get the trip that this review is for.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    /**
     * Get the passenger who wrote the review.
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class, 'passenger_id');
    }

    /**
     * Get the driver who is being reviewed.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
