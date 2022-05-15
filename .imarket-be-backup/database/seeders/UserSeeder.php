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

        $user = User::factory()->create();
        $user->assignRole('Super Admin');
        
        $x = 1;

        while($x <= 25){
            $user = User::factory()->create();
            $user->assignRole('User');
            $x++;
        }
    }
}
