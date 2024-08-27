<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'order_date' => $this->faker->dateTime,
            'total' => $this->faker->randomFloat(2, 0, 1000),
            // 'status' => $this->faker->randomElement(['active', 'inactive']),
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),

        ];
    }
}
