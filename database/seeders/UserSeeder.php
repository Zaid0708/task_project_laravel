<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create a default user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'super',
            'email' => 'zaid@yahoo.com',
            'password' => Hash::make('123456'), 
            'role_id' =>1, 
        ]);
    }
}