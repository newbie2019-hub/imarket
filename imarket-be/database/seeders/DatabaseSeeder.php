<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            ProductRatingSeeder::class,
            ProductTagsSeeder::class
        ]);
    }
}
