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
        // Schema::create('brands', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('slug')->unique();
        //     $table->string('website')->nullable();
        //     $table->longText('description')->nullable();
        //     $table->unsignedSmallInteger('position')->default(0);
        //     $table->boolean('is_visible')->default(false);
        //     $table->string('seo_title', 60)->nullable();
        //     $table->string('seo_description', 160)->nullable();
        //     $table->integer('sort')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('brands');
    }
};
