<?php

namespace Database\Seeders;

use App\Models\Summery;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SummerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //مادة الرياضيات المتتاليات
        Summery::create([
            'name' => 'طرق تعريف المتتاليات',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);
        Summery::create([
            'name' => 'المتتاليات الحسابية والهندسية',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);
        Summery::create([
            'name' => 'الاثبات بالتدريج',
            'file' => 'file.pdf',
            'lesson_id' => 1
        ]);

        //مادة الرياضيات النهايات
        Summery::create([
            'name' => 'مبرهنات الاحاطة',
            'file' => 'file.pdf',
            'lesson_id' => 2
        ]);
        Summery::create([
            'name' => 'نهاية تابع مركب',
            'file' => 'file.pdf',
            'lesson_id' => 2
        ]);

        //ادبي عربي فن الرواية
        Summery::create([
            'name' => 'القراءة التمهيدية 1',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Summery::create([
            'name' => 'القراءة التمهيدية 2',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Summery::create([
            'name' => 'المصابيح الزرق',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Summery::create([
            'name' => 'عوامل تجديد الرواية 1',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
        Summery::create([
            'name' => 'عوامل تجديد الرواية 2',
            'file' => 'file.pdf',
            'lesson_id' => 10
        ]);
    }
}
