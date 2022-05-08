<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $partner = Partner::factory()->create();
        
        return [
            'partner_id' => $partner->id,
            'user_id' => $partner->user_id,
            'name' => $this->faker->company,
            'description' => $this->faker->text,
            'address' => $this->faker->address,
            'week_days_opening' => "8:00",
            'week_days_closing' => "18:30",
            'week_end_opening' => "8:30",
            'week_end_closing' => "18:00",
            'status' => $this->faker->randomElement(['For Approval', 'Approved'])
        ];
    }
}
