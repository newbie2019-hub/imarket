<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInfo>
 */
class UserInfoFactory extends Factory
{
 
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = Faker::create('en_PH');

        $gender = $this->faker->randomElement(['Male', 'Female']);
        $first_name = $this->faker->firstName($gender);

        return [
            'first_name' => $first_name,
            'last_name' => $this->faker->lastName,
            'address' => $faker->address,
            'gender' => $gender,
            'contact_number' => $faker->mobileNumber(),
            'birthday' => $this->faker->dateTimeBetween('Y-m-d', '1999-03-20'),
            'profile_img' => $this->faker->image('public\images\profiles', 640, 480, null, false, true, $first_name),
        ];
    }
}
