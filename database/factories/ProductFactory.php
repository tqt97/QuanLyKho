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
            'product_title' => $this->faker->name,
            // 'sell_title' => $title_product,
            'slug' => \Illuminate\Support\Str::slug($title_product),
            'description' => $this->faker->sentence,
            'dosage' => $this->faker->randomNumber(1, true),
            'expiry_date' => '06-2028',
            'qty_per_product' => $this->faker->randomNumber(3, false),
            'original_price' => $this->faker->randomNumber(6, true),
            'sell_price' => $this->faker->randomNumber(6, true),
            'image' => 'https://images.unsplash.com/photo-1651950519238-15835722f8bb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8Mjh8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=300&q=60',
        ];
    }
}
