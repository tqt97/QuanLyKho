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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // title
            $table->string('common_title')->nullable();
            $table->string('product_title')->nullable();
            // $table->string('sell_title');
            $table->string('slug')->unique();

            // category
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();

            // additional information
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->text('dosage')->nullable(); // lieu dung
            $table->string('duration')->nullable(); // thoi gian su dung
            $table->string('expiry')->nullable(); // ngay het han
            $table->string('unit')->nullable(); // don vi

            // price
            $table->double('original_price')->nullable();
            $table->double('sell_price')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        // DB::statement(
        //     'ALTER TABLE products ADD FULLTEXT fulltext_index(common_title, product_title)'
        // );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
