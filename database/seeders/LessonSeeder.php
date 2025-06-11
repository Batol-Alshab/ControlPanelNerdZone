<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //الرياضيات
        Lesson::create([
            'name' => 'المتتاليات',
            'material_id' => 1,
        ]);
        Lesson::create([
            'name' => 'النهايات',
            'material_id' => 1,
        ]);
        Lesson::create([
            'name' => 'الاشتقاق',
            'material_id' => 1,
        ]);
        Lesson::create([
            'name' => 'التابع اللوغاريتمي',
            'material_id' => 1,
        ]);
        Lesson::create([
            'name' => 'التابع الاسي',
            'material_id' => 1,
        ]);
        Lesson::create([
            'name' => 'التكامل',
            'material_id' => 1,
        ]);

        //عربي علمي
        Lesson::create([
            'name' => 'فن الرواية',
            'material_id' => 4,
        ]);

        //تاريخ
        Lesson::create([
            'name' => 'متغيرات صنعت التاريخ الحديث',
            'material_id' => 11,
        ]);
        Lesson::create([
            'name' => 'تنبه فكري',
            'material_id' => 11,
        ]);
        // عربي ادبي
        Lesson::create([
            'name' => 'فن الرواية',
            'material_id' => 13,
        ]);

    }
}
