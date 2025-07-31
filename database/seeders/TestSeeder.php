<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //مادة الرياضيات المتتاليات
        Test::create([
            'name' => 'طرق تعريف المتتاليات',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 1,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'المتتاليات الحسابية والهندسية',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 1,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'الاثبات بالتدريج',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 1,
            'returned_cost' => '10',
        ]);

        //مادة الرياضيات النهايات
        Test::create([
            'name' => 'مبرهنات الاحاطة',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 2,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'نهاية تابع مركب',
            'numQuestions' => 2,
            'lesson_id' => 2,
            'returned_cost' => '10',
        ]);

        //ادبي عربي فن الرواية
        Test::create([
            'name' => 'القراءة التمهيدية 1',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 10,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'القراءة التمهيدية 2',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 10,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'المصابيح الزرق',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 10,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'عوامل تجديد الرواية 1',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 10,
            'returned_cost' => '10',
        ]);
        Test::create([
            'name' => 'عوامل تجديد الرواية 2',
            'numQuestions' => 2,
            'is_complete' => 0,
            'lesson_id' => 10,
            'returned_cost' => '10',
        ]);
    }
}
