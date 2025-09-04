<?php
namespace Database\Seeders;

use App\Models\Video;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $lessons = Lesson::all();

        // Create 3 videos for each lesson.
        foreach ($lessons as $lesson) {
            for ($i = 1; $i <= 3; $i++) {
                Video::create([
                    // Generate a video name based on the lesson name.
                    'name'      => 'فيديو ' . $i . ' - ' . $lesson->name,

                    // Use Faker to generate a dummy URL for the video.
                    'video'     => 'storage/app/public/videos/video_' . $i . '_' . $lesson->id . '.mp4',

                    // Associate the video with the current lesson.
                    'lesson_id' => $lesson->id,
                ]);
            }

            // //مادة الرياضيات المتتاليات
            // Video::create([
            //     'name' => 'طرق تعريف المتتاليات',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 1
            // ]);
            // Video::create([
            //     'name' => 'المتتاليات الحسابية والهندسية',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 1
            // ]);
            // Video::create([
            //     'name' => 'الاثبات بالتدريج',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 1
            // ]);

            // //مادة الرياضيات النهايات
            // Video::create([
            //     'name' => 'مبرهنات الاحاطة',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 2
            // ]);
            // Video::create([
            //     'name' => 'نهاية تابع مركب',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 2
            // ]);

            // //ادبي عربي فن الرواية
            // Video::create([
            //     'name' => 'القراءة التمهيدية 1',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 10
            // ]);
            // Video::create([
            //     'name' => 'القراءة التمهيدية 2',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 10
            // ]);
            // Video::create([
            //     'name' => 'المصابيح الزرق',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 10
            // ]);
            // Video::create([
            //     'name' => 'عوامل تجديد الرواية 1',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 10
            // ]);
            // Video::create([
            //     'name' => 'عوامل تجديد الرواية 2',
            //     'video' => 'video.mp4',
            //     'lesson_id' => 10
            // ]);

        }
    }
}
