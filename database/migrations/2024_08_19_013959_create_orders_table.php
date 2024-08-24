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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 32)->unique();

            // customer_id is nullable because the customer can be a guest
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();

            // customer phone number is required because the customer can be a guest
            // $table->json('customer_phone')->nullable()->unique()->index();
            // $table->string('customer_name')->nullable();

            // user_id is nullable because the order can be created by a guest
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('bonus_id')->nullable()->constrained('bonuses')->nullOnDelete();

            // Prices
            $table->double('total_price')->default(0)->nullable();
            // $table->double('discount')->default(0)->nullable();
            // $table->double('vat')->default(0)->nullable();

            // Status of customer when creating the order (new or old)
            // $table->enum('customer_status', ['new', 'old'])->default('new');

            // Status of order
            $table->enum('status', ['created', 'success', 'fail', 'not_answered', 'next_time'])->default('created');
            // Notes
            $table->text('notes')->nullable();

            // check customer buy or not
            // $table->boolean('is_buy')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
