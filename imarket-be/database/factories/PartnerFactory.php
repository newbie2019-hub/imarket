<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create();
        $user->assignRole('Seller');
        
        return [
            'user_id' => $user->id,
            'request_date' => Carbon::now(),
            // 'status' => $this->faker->randomElement(['For Approval', 'Approved'])
        ];
    }
}
