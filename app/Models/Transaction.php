<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'user_membership_id',
        'user_id',
        'amount',
        'transaction_type',
        'payment_method',
        'payment_proof_image',
        'transaction_reference',
        'verification_status',
        'verified_by',
        'verified_at',
        'notes',
        'is_offline',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'is_offline' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function userMembership()
    {
        return $this->belongsTo(UserMembership::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function isDuplicateReference()
    {
        if (!$this->transaction_reference) {
            return false;
        }

        return static::where('transaction_reference', $this->transaction_reference)
            ->where('id', '!=', $this->id ?? 0)
            ->exists();
    }
}
