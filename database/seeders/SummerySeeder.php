<?php
namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Summery;
use Illuminate\Database\Seeder;

class SummerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $lessons = Lesson::all();

        // Create 2 summary records for each lesson.
        foreach ($lessons as $lesson) {
            for ($i = 1; $i <= 2; $i++) {
                Summery::create([
                    // Generate a name based on the lesson.
                    'name'      => 'ملخص ' . $i . ' - ' . $lesson->name,

                    // Set a custom file path for the summary.
                    'file'      => 'SummeryFile/summary_' . $i . '_' . $lesson->id . '.pdf',

                    // Associate the summary with the current lesson.
                    'lesson_id' => $lesson->id,
                ]);
            }
        }

        // //مادة الرياضيات المتتاليات
        // Summery::create([
        //     'name' => 'طرق تعريف المتتاليات',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 1
        // ]);
        // Summery::create([
        //     'name' => 'المتتاليات الحسابية والهندسية',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 1
        // ]);
        // Summery::create([
        //     'name' => 'الاثبات بالتدريج',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 1
        // ]);

        // //مادة الرياضيات النهايات
        // Summery::create([
        //     'name' => 'مبرهنات الاحاطة',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 2
        // ]);
        // Summery::create([
        //     'name' => 'نهاية تابع مركب',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 2
        // ]);

        // //ادبي عربي فن الرواية
        // Summery::create([
        //     'name' => 'القراءة التمهيدية 1',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 10
        // ]);
        // Summery::create([
        //     'name' => 'القراءة التمهيدية 2',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 10
        // ]);
        // Summery::create([
        //     'name' => 'المصابيح الزرق',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 10
        // ]);
        // Summery::create([
        //     'name' => 'عوامل تجديد الرواية 1',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 10
        // ]);
        // Summery::create([
        //     'name' => 'عوامل تجديد الرواية 2',
        //     'file' => 'file.pdf',
        //     'lesson_id' => 10
        // ]);
    }
}
