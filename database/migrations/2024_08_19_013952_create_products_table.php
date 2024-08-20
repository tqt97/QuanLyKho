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
            $table->string('sell_title');
            $table->string('slug')->unique();

            // additional information
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->text('dosage')->nullable(); // lieu dung
            $table->date('expiry_date')->nullable(); // ngay het han
            $table->text('qty_per_product')->nullable(); // so luong trong 1 goi

            // price
            $table->double('original_price', 10, 2);
            $table->double('sell_price', 10, 2);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
