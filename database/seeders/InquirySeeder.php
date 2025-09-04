<?php
namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Inquiry;
use App\Models\Summery;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{

    public function run(): void
    {
        $user = User::create([
            'name'       => 'haneen',
            'password'   => '123456',
            'email'      => 'haneen@bak.com',
            'city'       => 'Hama',
            'sex'        => 0,
            'section_id' => '1',
        ]);
        $id        = $user->id;
        $videos    = Video::all();
        $summaries = Summery::all();

        // Combine videos and summaries into a single collection for seeding.
        $inquiryables = $videos->concat($summaries);

        foreach ($inquiryables as $inquiryable) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $inquiry = Inquiry::create([
                    'inquiry'          => 'Sample inquiry about ' . $inquiryable->name,
                    'user_id'          => $id,
                    'status'           => 'No Answer',
                    'inquiryable_id'   => $inquiryable->id,
                    'inquiryable_type' => get_class($inquiryable),
                ]);

                // Create an answer for some inquiries to show both statuses.
                if (rand(0, 1) === 1) {
                    Answer::create([
                        'inquiry_id'     => $inquiry->id,
                        'answer_content' => 'Sample answer for the inquiry about ' . $inquiryable->name,
                    ]);
                    // Update the inquiry status if it has an answer.
                    $inquiry->update(['status' => 'Complete Answer']);
                }
            }
        }
    }
}
