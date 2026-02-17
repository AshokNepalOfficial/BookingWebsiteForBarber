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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('role')->constrained()->onDelete('set null');
            $table->string('position', 100)->nullable()->after('role_id');
            $table->text('bio')->nullable()->after('position');
            $table->string('profile_image')->nullable()->after('bio');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('profile_image');
            $table->date('hire_date')->nullable()->after('hourly_rate');
            $table->boolean('is_active')->default(true)->after('hire_date');
            $table->boolean('can_accept_bookings')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'role_id',
                'position',
                'bio',
                'profile_image',
                'hourly_rate',
                'hire_date',
                'is_active',
                'can_accept_bookings'
            ]);
        });
    }
};
