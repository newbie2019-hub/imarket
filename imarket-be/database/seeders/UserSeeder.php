<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::factory()->create([
            'email' => 'admin@admin.com'
        ]);
        $user->assignRole('Super Admin');
        
        $x = 1;

        while($x <= 15){
            $user = User::factory()->create();
            $user->assignRole('User');
            $x++;
        }

        $y = 1;

        while($y <= 8){
            $user = User::factory()->create();
            $user->assignRole('Rider');
            $y++;
        }
    }
}
