<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductInfo>
 */
class ProductInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($this->faker));
        $this->faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($this->faker));
        
        $food = [
            $this->faker->foodName(),
            $this->faker->fruitName(),
            $this->faker->meatName(),
            $this->faker->beverageName(),
            $this->faker->vegetableName(),
            $this->faker->dairyName(),
        ];

        return [
           'name' => $food[$this->faker->numberBetween(0, 5)],
           'description' => $this->faker->text,
           'image' => $this->faker->image(public_path('images/products'), 640, 480, false),
           'category_id' => $this->faker->numberBetween(1, 12),
           'price' => $this->faker->numberBetween(25, 1450),
           'quantity' => $this->faker->numberBetween(1, 120)
        ];
    }
}
