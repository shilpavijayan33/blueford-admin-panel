<?php

use Illuminate\Database\Seeder;

class UserWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\UserWallet::create([
         	'user_id' =>1,
        	'balance' => 0,   
        	
        ]);

        App\UserWallet::create([
            'user_id' =>2,
            'balance' => 0,   
            
        ]);

        App\UserWallet::create([
            'user_id' =>3,
            'balance' => 0,   
            
        ]);

        App\UserWallet::create([
            'user_id' =>4,
            'balance' => 0,   
            
        ]);

        App\UserWallet::create([
            'user_id' =>5,
            'balance' => 0,   
            
        ]);
    }
}
