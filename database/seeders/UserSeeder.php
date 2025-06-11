<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'=>'Admin',
            'password'=>'batol',
            'email'=>'batol@sh.com',
            'city' => 'Hama',
            'sex'=>0,
            ]);
        $user->assignRole('admin');

        $user= User::factory()->count(2)->create()
                ->each(function ($user)
                {
                    $user->assignRole('student');
                });
        // User::create([
        //     'name'=>'batol',
        //     'password'=>'batol',
        //     'email'=>'batol1@sh.com',
        //     'city' => 'Hama',
        //     'sex'=>0,
        //     'section_id'=>'1'
        // ]);
        // User::create([
        //     'name'=>'batol',
        //     'password'=>'batol',
        //     'email'=>'batol2@sh.com',
        //     'city' => 'Hama',
        //     'sex'=>0,
        //     'section_id'=>'1'
        // ]);
        // User::create([
        //     'name'=>'batol',
        //     'password'=>'batol',
        //     'email'=>'batol3@sh.com',
        //     'city' => 'Aleppo',
        //     'sex'=>2,
        //     'section_id'=>'2'
        // ]);
        // User::create([
        //     'name'=>'batol',
        //     'password'=>'batol',
        //     'email'=>'batol4@sh.com',
        //     'city' => 'Aleppo',
        //     'sex'=>1,
        //     'section_id'=>'2'
        // ]);

    }
}
