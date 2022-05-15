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
            'logo' => $this->faker->image('public\images\logos', 480, 480, null, false, true, 'Store Logo', true),
            'banner_image' => $this->faker->image('public\images\banners', 640, 480, null, false, true, 'Banner Image', true),
            'status' => $partner->status == 'For Approval' ? 'For Approval' : 'Approved',
            'opening_status' => $this->faker->randomElement(['Automatic', 'Open', 'Closed'])
        ];
    }
}
