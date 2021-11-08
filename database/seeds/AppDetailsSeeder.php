<?php

use Illuminate\Database\Seeder;

class AppDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\AppDetails::create([
         	'name' =>'Blueford',
        	'mobile' => '1234567891',   
        	'email' =>'blueford@test.com',   
        	'address' => 'Test, calict',  
        	'logo' => 	'blueford.jpg',
            'salesman_slab' =>10,
            'plumber_slab' =>10,
        	
        ]);

    }
}
