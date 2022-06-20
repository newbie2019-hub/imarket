<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'adobo',
            'tinola',
            'sinigang',
            'curry',
            'chicken curry',
            'menudo',
            'caldereta',
            'sinugba',
            'prinito',
            'fried',
            'ginataan',
            'beef steak',
            'steak',
            'barbeque',
            'bbq',
            'sweet and sour',
            'atsara',
            'humba',
            'homba',
            'bagnet',
            'paksiw',
            'lechon',
            'litson',
            'liempo',
            'dinuguan',
            'spaghetti',
            'kare kare',
            'kare-kare',
            'sisig',
            'ginisa',
            'sauted',
            'saute',
            'chopsuey',
            'torta',
            'pakbet',
            'kinilaw',
            'kilaw',
            'sushi',
            'caldereta',
            'kaldereta',
            'bulalo',
            'pares',
            'mechado',
            'nilaga',
            'papaitan',
            'asado',
            'tapsilog',
            'longsilog',
            'meatballs',
        ];

       
        $productCount = Product::count();

        for($i = 1; $i < $productCount; $i++){

            $tags = array_rand($data, 15);
            
            foreach($tags as $tag){
                ProductTag::create([
                    'product_id' => $i,
                    'tag' => $data[$tag]
                ]);
            }
        }
    }
}
