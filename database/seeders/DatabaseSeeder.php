<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Section::create([
        //     'name' => 'scientific']);
        // Section::create([
        //         'name' => 'literary']);
        $this->call([
            // UserSeeder::class,
            // RoleSeeder::class,
            // PermissionSeeder::class,
            $user= User::factory()->count(30)->create()
                ->each(function ($user)
                {
                    $user->assignRole(rand(2,3)==2 ? 'teacher': 'student');
                }),


        ]);
    }
}
