<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super-admin']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        
        // Dodeli sve dozvole Super Admin-u
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
