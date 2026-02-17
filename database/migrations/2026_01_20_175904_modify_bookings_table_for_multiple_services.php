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
            // Drop the service_id foreign key and column
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
            
            // Add barber_id (optional - can be null if "any barber")
            $table->foreignId('barber_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add back service_id
            $table->foreignId('service_id')->after('user_id')->constrained()->onDelete('cascade');
            
            // Drop barber_id
            $table->dropForeign(['barber_id']);
            $table->dropColumn('barber_id');
        });
    }
};
