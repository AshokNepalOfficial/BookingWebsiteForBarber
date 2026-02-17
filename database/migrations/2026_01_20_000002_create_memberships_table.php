<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('membership_name', 200);
            $table->text('description')->nullable();
            $table->integer('duration_days');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->integer('free_services_count')->default(0);
            $table->boolean('priority_booking')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
