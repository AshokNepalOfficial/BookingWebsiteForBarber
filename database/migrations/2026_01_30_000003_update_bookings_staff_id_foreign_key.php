<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the old foreign key constraint to staff table if it exists
            if (Schema::hasColumn('bookings', 'staff_id')) {
                try {
                    $table->dropForeign(['staff_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                
                // Add new foreign key constraint to users table
                $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'staff_id')) {
                try {
                    $table->dropForeign(['staff_id']);
                } catch (\Exception $e) {
                    // Ignore
                }
                
                // Restore foreign key to staff table
                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
            }
        });
    }
};
