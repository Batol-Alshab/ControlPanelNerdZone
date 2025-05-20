<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create users ']);
        Permission::firstOrCreate(['name' => 'update users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        Permission::firstOrCreate(['name' => 'create sections ']);
        Permission::firstOrCreate(['name' => 'update articles']);
        Permission::firstOrCreate(['name' => 'delete articles']);
    }
}
