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
            $table->string('common_title');
            $table->string('product_title');
            // $table->string('sell_title');
            $table->string('slug')->unique();

            // additional information
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->text('dosage')->nullable(); // lieu dung
            $table->string('expiry')->nullable(); // ngay het han
            $table->string('qty_per_product')->nullable(); // so luong trong 1 goi

            // price
            $table->double('original_price')->nullable();
            $table->double('sell_price');

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
