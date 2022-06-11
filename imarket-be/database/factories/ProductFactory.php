<?php

namespace Database\Factories;

use App\Models\ProductInfo;
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
    public function definition()
    {
        return [
            'store_id' => $this->faker->numberBetween(1, 10),
            'product_info_id' => ProductInfo::factory(),
        ];
    }
}
