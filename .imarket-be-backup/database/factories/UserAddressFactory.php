<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lat' => null,
            'lng' => null,
            'street_number' => null,
            'route' => null,
            'locality' => 'Tacloban City',
            'adminstrative_area_level_2' => 'Eastern Visayas',
            'adminstrative_area_level_1' => 'Leyte',
            'country' => 'Philippines',
            'formatted_address' => null,
        ];
    }
}
