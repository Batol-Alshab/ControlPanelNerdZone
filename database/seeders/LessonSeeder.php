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
            'cost' => 1,
            'name' => 'المتتاليات',
            'material_id' => 1,
        ]);
        Lesson::create([
            'cost' => 1,
            'name' => 'النهايات',
            'material_id' => 1,
        ]);
        Lesson::create([
            'cost' => 0,
            'name' => 'الاشتقاق',
            'material_id' => 1,
        ]);
        Lesson::create([
            'cost' => 0,
            'name' => 'التابع اللوغاريتمي',
            'material_id' => 1,
        ]);
        Lesson::create([
            'cost' => 0,
            'name' => 'التابع الاسي',
            'material_id' => 1,
        ]);
        Lesson::create([
            'cost' => 0,
            'name' => 'التكامل',
            'material_id' => 1,
        ]);

        //عربي علمي
        Lesson::create([
            'cost' => 0,
            'name' => 'فن الرواية',
            'material_id' => 4,
        ]);

        //تاريخ
        Lesson::create([
            'cost' => 1,
            'name' => 'متغيرات صنعت التاريخ الحديث',
            'material_id' => 11,
        ]);
        Lesson::create([
            'cost' => 0,
            'name' => 'تنبه فكري',
            'material_id' => 11,
        ]);
        // عربي ادبي
        Lesson::create([
            'cost' => 1,
            'name' => 'فن الرواية',
            'material_id' => 13,
        ]);
    }
}
