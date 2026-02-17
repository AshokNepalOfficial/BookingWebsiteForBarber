<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    protected $fillable = [
        'user_id',
        'membership_id',
        'start_date',
        'end_date',
        'remaining_free_services',
        'status',
        'payment_transaction_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'remaining_free_services' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(Transaction::class, 'payment_transaction_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= now()->toDateString();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>=', now()->toDateString());
    }
}
