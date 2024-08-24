<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Tag;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prvo učitamo sve proizvode i tagove
        $products = Product::all();
        $tags = Tag::all();

        // Dodajemo nasumične veze između proizvoda i tagova
        foreach ($products as $product) {
            // Odaberemo nasumičan broj tagova po proizvodu (npr. 1 do 3)
            $randomTags = $tags->random(rand(1, 3))->pluck('id');

            // Povezujemo proizvod sa odabranim tagovima
            $product->tags()->attach($randomTags);
        }
    }
}
