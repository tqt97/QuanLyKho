<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title_product = $this->faker->name;
        return [
            'common_title' => $title_product,
            'product_title' => $title_product,
            // 'sell_title' => $title_product,
            'slug' => \Illuminate\Support\Str::slug($title_product),
            'description' => $this->faker->sentence,
            'dosage' => $this->faker->randomNumber(),
            'expiry_date' => $this->faker->dateTime,
            'qty_per_product' => $this->faker->randomNumber(),
            'original_price' => $this->faker->randomNumber(),
            'sell_price' => $this->faker->randomNumber(),
        ];
    }
}
