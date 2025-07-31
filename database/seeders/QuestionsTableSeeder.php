<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   $questions = json_decode(file_get_contents(database_path('data/questions.json')), true);

        foreach ($questions['questions'] as $qu) {
            Question::firstOrCreate(
                [
                    "content" => $qu['content'],
                    "image" => $qu['image'],
                    "test_id" => $qu['test_id'],
                    "option_1" => $qu['option_1'],
                    "option_2" => $qu['option_2'],
                    "option_3" => $qu['option_3'],
                    "option_4" => $qu['option_4'],
                    "correct_option" => $qu['correct_option'],

                ]
            );
        }
//         DB::table('questions')->insert([
//     [
//         'content' => 'Which element has the chemical symbol O?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'Gold',
//         'option_2' => 'Oxygen',
//         'option_3' => 'Osmium',
//         'option_4' => 'Carbon',
//         'correct_option' => 2,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'How many continents are there on Earth?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => '5',
//         'option_2' => '6',
//         'option_3' => '7',
//         'option_4' => '8',
//         'correct_option' => 3,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'In which year did the Titanic sink?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => '1912',
//         'option_2' => '1920',
//         'option_3' => '1905',
//         'option_4' => '1918',
//         'correct_option' => 1,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'Which language is primarily spoken in Brazil?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'Spanish',
//         'option_2' => 'French',
//         'option_3' => 'Portuguese',
//         'option_4' => 'Italian',
//         'correct_option' => 3,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'What is the square root of 144?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => '12',
//         'option_2' => '14',
//         'option_3' => '16',
//         'option_4' => '10',
//         'correct_option' => 1,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'Who painted the Mona Lisa?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'Vincent Van Gogh',
//         'option_2' => 'Pablo Picasso',
//         'option_3' => 'Claude Monet',
//         'option_4' => 'Leonardo da Vinci',
//         'correct_option' => 4,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'Which gas do plants absorb from the atmosphere?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'Oxygen',
//         'option_2' => 'Carbon Dioxide',
//         'option_3' => 'Nitrogen',
//         'option_4' => 'Hydrogen',
//         'correct_option' => 2,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'Which planet is the largest in our solar system?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'Earth',
//         'option_2' => 'Saturn',
//         'option_3' => 'Jupiter',
//         'option_4' => 'Uranus',
//         'correct_option' => 3,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'Who is the author of “1984”?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => 'George Orwell',
//         'option_2' => 'J.K. Rowling',
//         'option_3' => 'Aldous Huxley',
//         'option_4' => 'Ray Bradbury',
//         'correct_option' => 1,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'content' => 'What is the boiling point of water in Celsius?',
//         'image' => null,
//         'test_id' => 1,
//         'option_1' => '90°C',
//         'option_2' => '95°C',
//         'option_3' => '100°C',
//         'option_4' => '105°C',
//         'correct_option' => 3,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]
// ]);
//     }
}
}
