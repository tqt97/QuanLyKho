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
            'title_popular' => $title_product,
            'title_product' => $title_product,
            'slug' => \Illuminate\Support\Str::slug($title_product),
            'description' => $this->faker->sentence,
            'dosage' => $this->faker->randomNumber(),
            'expiry_date' => $this->faker->dateTime,
            'quantity_per_pack' => $this->faker->randomNumber(),
            // 'category_id' => \App\Models\Category::factory(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'is_active' => $this->faker->boolean,
        ];
    }
}
