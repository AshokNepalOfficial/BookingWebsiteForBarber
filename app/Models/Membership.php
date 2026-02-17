<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'membership_name',
        'description',
        'duration_days',
        'price',
        'discount_percentage',
        'free_services_count',
        'priority_booking',
        'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'free_services_count' => 'integer',
        'priority_booking' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function activeMemberships()
    {
        return $this->hasMany(UserMembership::class)->where('status', 'active');
    }

    // Accessor for view compatibility
    public function getNameAttribute()
    {
        return $this->membership_name;
    }

    public function getFreeServicesAttribute()
    {
        return $this->free_services_count;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
