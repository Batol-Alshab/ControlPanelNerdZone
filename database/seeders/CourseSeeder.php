<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => 'طرق تعريف المتتاليات',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);
        Course::create([
            'name' => 'المتتاليات الحسابية والهندسية',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);
        Course::create([
            'name' => 'الاثبات بالتدريج',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);

        //مادة الرياضيات النهايات
        Course::create([
            'name' => 'مبرهنات الاحاطة',
            'file' => 'file.pdf',
            'lesson_id' => 2
        ]);
        Course::create([
            'name' => 'نهاية تابع مركب',
            'file' => 'file.pdf',
            'lesson_id' => 2
        ]);

        //ادبي عربي فن الرواية
        Course::create([
            'name' => 'القراءة التمهيدية 1',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Course::create([
            'name' => 'القراءة التمهيدية 2',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Course::create([
            'name' => 'المصابيح الزرق',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Course::create([
            'name' => 'عوامل تجديد الرواية 1',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Course::create([
            'name' => 'عوامل تجديد الرواية 2',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
    }
}
