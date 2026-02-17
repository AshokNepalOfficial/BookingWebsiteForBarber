<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_no',
        'password',
        'role',
        'is_guest',
        'walkin_token',
        'loyalty_points',
        'role_id',
        'position',
        'bio',
        'profile_image',
        'hourly_rate',
        'hire_date',
        'is_active',
        'can_accept_bookings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_guest' => 'boolean',
            'loyalty_points' => 'integer',
            'is_active' => 'boolean',
            'can_accept_bookings' => 'boolean',
            'hire_date' => 'date',
            'hourly_rate' => 'decimal:2',
        ];
    }

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Role assigned to this user (for staff members)
     */
    public function roleDetails()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has a specific permission through their role
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role_id || !$this->roleDetails) {
            return false;
        }
        
        return $this->roleDetails->hasPermission($permissionName);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all permissions for this user through their role
     */
    public function permissions()
    {  
        return $this->roleDetails ? $this->roleDetails->permissions : collect();
    }

    /**
     * Bookings assigned to this staff member (when acting as staff)
     */
    public function staffBookings()
    {
        return $this->hasMany(Booking::class, 'staff_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeMembership()
    {
        return $this->hasOne(UserMembership::class)->where('status', 'active')
            ->where('end_date', '>=', now());
    }
}
