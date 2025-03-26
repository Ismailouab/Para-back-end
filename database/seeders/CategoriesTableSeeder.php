<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Chaussures', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cheville', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cou', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coude', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuisse et hanche', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Epaule', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Genou', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pied', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Poignet, main et doigt', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ventre', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
