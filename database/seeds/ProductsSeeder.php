<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Products::create([
         	'name' =>'Shampoo',
            'model' =>'DV123',
        	'colour' => 'White',   
        	'size' =>'2 3',   
        	'description' => 'Shampoo and conditioner',   	
        	'images' => '["79492533595.jpg"]',  
        ]);

    }
}
