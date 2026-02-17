<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'staff_id',
        'barber_id',
        'appointment_date',
        'appointment_time',
        'status',
        'payment_status',
        'special_request',
        'confirmed_at',
        'completed_at',
        'discount_amount',
        'discount_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service');
    }

    // Helper method to get first service (for backward compatibility)
    public function getFirstServiceAttribute()
    {
        return $this->services->first();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isEditableByCustomer()
    {
        if ($this->status !== 'pending') {
            return false;
        }
        
        $minutesSinceCreation = Carbon::parse($this->created_at)->diffInMinutes(now());
        return $minutesSinceCreation <= 20;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
