<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Database\Seeders\TestSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\SectionSeeder;
use Database\Seeders\SummerySeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\QuestionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $this->call([

            SectionSeeder::class,
            MaterialSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            LessonSeeder::class,
            SummerySeeder::class,
            CourseSeeder::class,
            VideoSeeder::class,
            TestSeeder::class,
            QuestionsTableSeeder::class

        ]);

    }
}
