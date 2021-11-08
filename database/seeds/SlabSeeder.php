<?php

use Illuminate\Database\Seeder;

class SlabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Slab::create([
         	'product_id' =>1,
            'start' =>1,
        	'end' => 10, 
            'value' =>10,

        ]);

         App\Slab::create([
            'product_id' =>1,
            'start' =>11,
            'end' => 20, 
            'value' =>20,

        ]);


        App\Slab::create([
            'product_id' =>1,
            'start' =>21,
            'end' => 30, 
            'value' =>30,

        ]);


        App\Slab::create([
            'product_id' =>1,
            'start' =>31,
            'end' => 40,  
            'value' =>40,

        ]);

        App\Slab::create([
            'product_id' =>1,
            'start' =>41,
            'end' => 50,  
            'value' =>50,

        ]);

        App\Slab::create([
            'product_id' =>1,
            'start' =>51,
            'end' => 60, 
            'value' =>60,

        ]);

         App\Slab::create([
            'product_id' =>1,
            'start' =>61,
            'end' => 70, 
            'value' =>70,

        ]);

        App\Slab::create([
            'product_id' =>1,
            'value' =>10,
            'type' =>'plumber'   
        ]);

        App\Slab::create([
            'product_id' =>1,
            'value' =>20,
            'type' =>'salesman'   
        ]);

      

    }
}
