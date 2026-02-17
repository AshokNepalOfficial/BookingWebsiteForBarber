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
        // Update the role enum to include 'manager' and 'barber'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'member', 'staff', 'receptionist', 'admin', 'manager', 'barber') NOT NULL DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'member', 'staff', 'receptionist', 'admin') NOT NULL DEFAULT 'customer'");
    }
};
