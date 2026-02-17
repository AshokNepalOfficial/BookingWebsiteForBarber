<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('url', 500);
            $table->string('method', 10)->default('GET');
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, tablet, desktop
            $table->string('browser', 100)->nullable();
            $table->string('platform', 100)->nullable(); // Windows, Mac, Linux, iOS, Android
            $table->string('referrer', 500)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('device_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
