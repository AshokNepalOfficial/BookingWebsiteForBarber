<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('phone_no', 20);
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->string('position', 100)->nullable(); // e.g., 'Senior Barber', 'Manager'
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->date('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('can_accept_bookings')->default(false); // For barbers
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
