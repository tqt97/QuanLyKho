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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // sort items
            $table->unsignedInteger('sort')->default(0);

            // Order
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            // Product
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Quantity
            $table->double('quantity');

            //Item Info
            $table->string('item')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->double('vat')->default(0)->nullable();
            $table->double('total')->default(0)->nullable();
            // $table->double('returned')->default(0)->nullable();

            // $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
