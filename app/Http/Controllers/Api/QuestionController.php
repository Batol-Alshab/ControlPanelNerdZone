<?php

namespace App\Http\Controllers\Api;

use App\Models\Test;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    use ApiResponseTrait;
    public function getQuestions($id)
    {

        $questions = Test::find($id)->questions()
            ->get()
            ->map(fn($question) => [
                'id' => $question->id,
                'content' => $question->content,
                'image' => $question->image,
                'option_1' => $question->option_1,
                'option_2' => $question->option_2,
                'option_3' => $question->option_3,
                'option_4' => $question->option_4,
            ]);
        return $this->successResponse($questions);
    }

    public function correctAsnwer(Request $request)
    {
        $answers =  $request->json()->all();
        $score = 0;
        $results = [];

        foreach ($answers as $answer) {
            $question = Question::find($answer['question_id']);

            if (!$question) {
                $results[] = [
                    'question_id' => $answer['question_id'],
                    'correct' => false,
                    'error' => 'Question not found'
                ];
                continue;
            }

            $isCorrect = ($question->correct_option == $answer['selected_option']);
            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question_id' => $question->id,
                'correct' => $isCorrect,
                'correct_option' => $question->correct_option
            ];
        }

        return response()->json([
            'score' => $score,
            'results' => $results
        ]);
    }
}
