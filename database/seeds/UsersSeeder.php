<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
        	'name' => 'System Admin',
        	'username' =>'admin',
        	'password' =>Hash::make('123456'),
        	'email' =>'admin@test.com',
            'sponsor' =>0,
            'admin' =>1,
            'mobile' =>'1234567891',
            'user_type' =>'admin',
        ]);

         App\User::create([
            'name' => 'Test Dealer',
            'username' =>'testdealer',
            'password' =>Hash::make('123456'),
            'email' =>'testdealer@test.com',
            'sponsor' =>1,
            'admin' =>0,
            'mobile' =>'1234567892',
            'user_type' =>'dealer',
        ]);

         App\User::create([
            'name' => 'Test Plumber',
            'username' =>'testplumber',
            'password' =>Hash::make('123456'),
            'email' =>'testplumber@test.com',
            'sponsor' =>1,
            'admin' =>0,
            'mobile' =>'1234567893',
            'user_type' =>'plumber',
        ]);

         App\User::create([
            'name' => 'Test Salesman',
            'username' =>'testsalesman',
            'password' =>Hash::make('123456'),
            'email' =>'testsalesman@test.com',
            'sponsor' =>2,
            'admin' =>0,
            'mobile' =>'1234567894',
            'user_type' =>'salesman',
        ]);
        
         App\User::create([
            'name' => 'Test Admin',
            'username' =>'testadmin',
            'password' =>Hash::make('123456'),
            'email' =>'testadmin@test.com',
            'sponsor' =>1,
            'admin' =>1,
            'mobile' =>'1234567895',
            'user_type' =>'admin',
        ]);

    }
}
