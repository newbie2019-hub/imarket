<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductTag>
 */
class ProductTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $data = [
            'adobo',
            'tinola',
            'sinigang',
            'curry',
            'chicken curry',
            'menudo',
            'caldereta',
            'sinugba',
            'prinito',
            'fried',
            'ginataan',
            'beef steak',
            'steak',
            'barbeque',
            'bbq',
            'sweet and sour',
            'atsara',
            'humba',
            'homba',
            'bagnet',
            'paksiw',
            'lechon',
            'litson',
            'liempo',
            'dinuguan',
            'spaghetti',
            'kare kare',
            'kare-kare',
            'sisig',
            'ginisa',
            'sauted',
            'saute',
            'chopsuey',
            'torta',
            'pakbet',
            'kinilaw',
            'kilaw',
            'sushi',
            'caldereta',
            'kaldereta',
            'bulalo',
            'pares',
            'mechado',
            'nilaga',
            'papaitan',
            'asado',
            'tapsilog',
            'longsilog',
            'meatballs',
        ];

        $tags = $this->faker->randomElements($data, 10);
        return [
            //
        ];
    }
}
