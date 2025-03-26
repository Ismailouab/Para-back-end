<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890', // Example phone number
            'address' => '123 Admin Street, City', // Example address  
            'role_id' => 1,  
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Alice',
            'email' => 'Alice@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321', // Example phone number
            'address' => '456 User Avenue, City', // Example address
            'role_id' => 2,  
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
