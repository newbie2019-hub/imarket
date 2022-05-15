<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductRating>
 */
class ProductRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
           'product_id' => $this->faker->numberBetween(1, 40),
           'user_id' => $this->faker->numberBetween(10, 22),
           'rating' => $this->faker->randomFloat(1, 1, 5),
           'comment' => $this->faker->paragraph(2)
        ];
    }
}
