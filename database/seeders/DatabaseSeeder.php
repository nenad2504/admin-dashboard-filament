<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleTableSeeder::class);

        // Pronađi korisnika kojem želiš dodeliti Super Admin ulogu
        $superAdmin = User::factory()->create([
            'name' => 'Nenad Jovanovic',
            'email' => 'admin@gmail.com',
            'password' => 'admin2244',
        ]);

        // Dodeli Super Admin ulogu korisniku
        $superAdmin->assignRole('super-admin');

        User::factory()->count(20)->create();

        $this->call(TagsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
