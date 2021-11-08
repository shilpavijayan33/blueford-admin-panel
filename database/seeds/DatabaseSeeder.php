<?php

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
        $this->call(UsersSeeder::class);
        $this->call(UserDetailsSeeder::class);
        $this->call(UserWalletSeeder::class);  
        $this->call(AppDetailsSeeder::class);    
        $this->call(ProductsSeeder::class);           
        $this->call(SlabSeeder::class);
             

    }
}
