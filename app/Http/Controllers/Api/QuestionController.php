<?php

namespace App\Http\Controllers\Api;

use App\Models\Test;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    use ApiResponseTrait;
    public function getQuestions($lesson_id, $test_id)
    {

        $lesson = Lesson::find($lesson_id);
        $questions = Test::find($test_id);
        if (! $questions) {
            return $this->errorResponse('غير متوفر اسئلة لهذا الاختبار', 404);
        }
        $user = Auth::guard(name: 'sanctum')->user();
        //زائر عادي والدرس مفتوح للكل
        if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {
            $questions = $questions->questions()
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
        return $this->successResponse('مقفولة');
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
