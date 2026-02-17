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
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // e.g., 'hero', 'about', 'contact'
            $table->string('key'); // e.g., 'title', 'subtitle', 'description'
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, url
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
