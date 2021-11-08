<?php

use Illuminate\Database\Seeder;

class UserDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\UserDetails::create([
        	'user_id' => 1,        	
        ]);

        App\UserDetails::create([
            'user_id' => 2,
            'address' =>'Dealer 1,Mavoor post,Kozhikode 673661',
            'district' =>'Kozhikode',
            'state' =>'Kerala',
            'shop_name' =>'Super Hyper Market',
            'shop_address' =>'Mavoor post,Kozhikode 673661',
            'shop_district' =>'Kozhikode',
            'shop_state' => 'Kerala',        
        ]);

        App\UserDetails::create([
            'user_id' => 3,  
             'address' =>'Plumber 1,Mavoor post,Kozhikode 673661',
            'district' =>'Kozhikode',
            'state' =>'Kerala',
            'shop_name' =>'Super Hyper Market',
            'shop_address' =>'Mavoor post,Kozhikode 673661',
            'shop_district' =>'Kozhikode',
            'shop_state' => 'Kerala',             
        ]);

        App\UserDetails::create([
            'user_id' => 4,   
             'address' =>'Salesman 1,Mavoor post,Kozhikode 673661',
            'district' =>'Kozhikode',
            'state' =>'Kerala',
            'shop_name' =>'Super Hyper Market',
            'shop_address' =>'Mavoor post,Kozhikode 673661',
            'shop_district' =>'Kozhikode',
            'shop_state' => 'Kerala',            
        ]);

        App\UserDetails::create([
            'user_id' => 5,         
        ]);
    }
}
