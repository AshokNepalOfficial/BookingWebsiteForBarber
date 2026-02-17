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
        // Migration not needed - settings table already uses key-value structure
        // Just need to seed the new branding settings
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to revert
    }
};
