<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'category' => 'Fruits'
            ],
            [
                'category' => 'Vegetables'
            ],
            [
                'category' => 'Spices'
            ],
            [
                'category' => 'Drinks'
            ],
            [
                'category' => 'Delicacy'
            ],
            [
                'category' => 'Sauce'
            ],
            [
                'category' => 'Meat'
            ],
            [
                'category' => 'Fish'
            ],
            [
                'category' => 'Dairy Products'
            ],
            [
                'category' => 'Pet Food'
            ],
            [
                'category' => 'Oils'
            ],
            [
                'category' => 'Bread'
            ],
        ];

        foreach($data as $category){
            Category::create($category);
        }
    }
}
