<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            DB::table('products')->insert([
                'name' => $faker->word,
                'category_id' => random_int(1,4),
                'price' => $faker->randomFloat(2, 1, 100), // Random float sa 2 decimale između 1 i 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}