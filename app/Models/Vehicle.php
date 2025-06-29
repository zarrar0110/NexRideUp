<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'make',
        'model',
        'year',
        'color',
        'plate_number',
        'capacity',
    ];

    /**
     * Get the driver that owns the vehicle.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
