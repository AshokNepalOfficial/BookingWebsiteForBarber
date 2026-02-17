<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'price',
        'duration_minutes',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
