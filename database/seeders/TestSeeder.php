<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [];

        for ($lessonId = 1; $lessonId <= 29; $lessonId++) {
            $tests[] = [
                'name' => "الاختبار الأول",
                'numQuestions' => 10,
                'is_complete' => true,
                'lesson_id' => $lessonId,
            ];
            $tests[] = [
                'name' => "الاختبار الثاني",
                'numQuestions' => 10,
                'is_complete' => true,
                'lesson_id' => $lessonId,
            ];
        }

        DB::table('tests')->insert($tests);



        // //مادة الرياضيات المتتاليات
        // Test::create([
        //     'name' => 'طرق تعريف المتتاليات',
        //     'numQuestions' => 2,
        //     'is_complete' => 0,
        //     'lesson_id' => 1
        // ]);
        // Test::create([
        //     'name' => 'المتتاليات الحسابية والهندسية',
        //     'numQuestions' => 2,
        // 'is_complete' => 0,
        //     'lesson_id' => 1
        // ]);
        // Test::create([
        //     'name' => 'الاثبات بالتدريج',
        //     'numQuestions' => 2,
        // 'is_complete' => 0,
        //     'lesson_id' => 1
        // ]);

        // //مادة الرياضيات النهايات
        // Test::create([
        //     'name' => 'مبرهنات الاحاطة',
        //     'numQuestions' => 2,
        // 'is_complete' => 0,
        //     'lesson_id' => 2
        // ]);
        // Test::create([
        //     'name' => 'نهاية تابع مركب',
        //     'numQuestions' => 2,
        //     'lesson_id' => 2
        // ]);

        // //ادبي عربي فن الرواية
        // Test::create([
        //     'name' => 'القراءة التمهيدية 1',
        //     'numQuestions' => 2,
        // 'is_complete' => 0,
        //     'lesson_id' => 10
        // ]);
        // Test::create([
        //     'name' => 'القراءة التمهيدية 2',
        //     'numQuestions' => 2,
        // 'is_complete' => 0,
        //     'lesson_id' => 10
        // ]);
        // Test::create([
        //     'name' => 'المصابيح الزرق',
        //     'numQuestions' => 2,
        //     'is_complete' => 0,
        //     'lesson_id' => 10
        // ]);
        // Test::create([
        //     'name' => 'عوامل تجديد الرواية 1',
        //     'numQuestions' => 2,
        //     'is_complete' => 0,
        //     'lesson_id' => 10
        // ]);
        // Test::create([
        //     'name' => 'عوامل تجديد الرواية 2',
        //     'numQuestions' => 2,
        //     'is_complete' => 0,
        //     'lesson_id' => 10
        // ]);


    }
}
