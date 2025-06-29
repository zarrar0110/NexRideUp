<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'trip_id',
        'amount',
        'method',
        'paid_at',
    ];

    /**
     * Get the trip that owns the payment.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
}
