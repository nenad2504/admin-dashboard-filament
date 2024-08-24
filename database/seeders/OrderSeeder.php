<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pretpostavlja se da već postoje korisnici i proizvodi u bazi
        $users = User::all()->pluck('id')->toArray();
        $products = Product::all()->pluck('id')->toArray();

        // Kreiranje 50 nasumičnih zapisa u orders tabeli
        Order::factory()->count(50)->create([
            'user_id' => function () use ($users) {
                return $users[array_rand($users)];
            },
            'product_id' => function () use ($products) {
                return $products[array_rand($products)];
            }
        ]);
    }
}

