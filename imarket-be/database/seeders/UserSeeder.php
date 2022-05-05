<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $x = 1;

        while($x <= 25){
            $userSeller = User::factory()->create();
            $userSeller->assignRole('User');
            $x++;
        }
    }
}
