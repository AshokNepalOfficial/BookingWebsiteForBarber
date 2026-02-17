<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from staff table to users table
        if (Schema::hasTable('staff')) {
            $staffMembers = DB::table('staff')->get();
            
            foreach ($staffMembers as $staff) {
                // Check if user with same email already exists
                $existingUser = DB::table('users')->where('email', $staff->email)->first();
                
                if (!$existingUser) {
                    DB::table('users')->insert([
                        'first_name' => $staff->first_name,
                        'last_name' => $staff->last_name,
                        'email' => $staff->email,
                        'phone_no' => $staff->phone_no,
                        'password' => $staff->password,
                        'role' => 'staff', // Default to staff, can be updated manually
                        'role_id' => $staff->role_id,
                        'position' => $staff->position,
                        'bio' => $staff->bio,
                        'profile_image' => $staff->profile_image,
                        'hourly_rate' => $staff->hourly_rate,
                        'hire_date' => $staff->hire_date,
                        'is_active' => $staff->is_active,
                        'can_accept_bookings' => $staff->can_accept_bookings,
                        'is_guest' => false,
                        'loyalty_points' => 0,
                        'remember_token' => $staff->remember_token,
                        'created_at' => $staff->created_at,
                        'updated_at' => $staff->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as we cannot distinguish
        // which users were originally staff members
    }
};
