<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Test;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    use ApiResponseTrait;
    public function getQuestions($lesson_id, $test_id)
    {
        try {
            $lesson = Lesson::find($lesson_id);

            $questions = Test::find($test_id);
            if (! $questions) {
                return $this->errorResponse('غير متوفر اسئلة لهذا الاختبار', 404);
            }
            $user = Auth::guard('sanctum')->user();
            //زائر عادي والدرس مفتوح للكل
            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {
                $questions = $questions->questions()
                    ->get()
                    ->map(fn($question) => [
                        'id'       => $question->id,
                        'content'  => $question->content,
                        'image'    => $question->image,
                        'option_1' => $question->option_1,
                        'option_2' => $question->option_2,
                        'option_3' => $question->option_3,
                        'option_4' => $question->option_4,
                    ]);
                return $this->successResponse($questions);
            }
            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
/*
    public function correctAsnwer(Request $request, $lesson_id, $test_id)
    {
        try {
            $answers      = $request->json()->all();
            $lesson       = Lesson::find($lesson_id);
            $material_id  = $lesson->material_id;
            $test         = Test::find($test_id);
            $score        = 0;
            $num_qusetion = $test->questions()->count();
            $results      = [];
            $passing_rate = 0;

            $user = Auth::guard(name: 'sanctum')->user();
            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {

                foreach ($answers as $answer) {
                    $question = Question::find($answer['question_id']);

                    if (! $question) {
                        $results[] = [
                            'question_id' => $answer['question_id'],
                            'correct'     => false,
                            'error'       => 'Question not found',
                        ];
                        continue;
                    }

                    $isCorrect = ($question->correct_option == $answer['selected_option']);
                    if ($isCorrect) {
                        $score++;
                    }

                    $results[] = [
                        'question_id'    => $question->id,
                        'correct'        => $isCorrect,
                        'correct_option' => $question->correct_option,
                    ];
                }

                // return $rate;
                if ($score >= ($num_qusetion / 2)) {
                    $passing_rate = (int) ($score * 100) / $num_qusetion;
                    //  $user->materials()->attach($m,['rate' => 10]);
                    if ($user && ! ($user->tests()->where('test_id', $test_id)->exists())) {
                        $old_rate = $user->materials()->first()->pivot['rate'];
                        $rate     = (int) ($passing_rate * $test->returned_cost) / 100;
                        $new_rate = $old_rate + $rate;
                        $user->tests()->attach($test_id, ['passing_rate' => $passing_rate]);
                        $user->materials()->syncWithoutDetaching([$material_id => ['rate' => $new_rate]]);
                        return response()->json([
                            'new_rate'     => round($rate),
                            'passing_rate' => $passing_rate,
                            'results'      => $results,
                        ]);
                    }
                    return response()->json([
                        'passing_rate' => $passing_rate,
                        'results'      => $results,
                    ]);
                }

                return $this->successResponse('الرجاء اعادة الاختبار لان عدد الاجابات الصحيحة غير كافي لتجاوز الاختبار');
            }
            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

*/
///////////////////
public function correctAsnwer(Request $request, $lesson_id, $test_id)
    {
        try {
            $answers      = $request->json()->all();
            $lesson       = Lesson::find($lesson_id);
            $material_id  = $lesson->material_id;
            $test         = Test::find($test_id);
            $score        = 0;
            $num_qusetion = $test->questions()->count();
            $results      = [];
            $passing_rate = 0;

            $user = Auth::guard(name: 'sanctum')->user();

            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {

                foreach ($answers as $answer) {
                    $question = Question::find($answer['question_id']);

                    if (! $question) {
                        $results[] = [
                            'question_id' => $answer['question_id'],
                            'correct'     => false,
                            'error'       => 'Question not found',
                        ];
                        continue;
                    }

                    $isCorrect = ($question->correct_option == $answer['selected_option']);
                    if ($isCorrect) {
                        $score++;
                    }

                    $results[] = [
                        'question_id'    => $question->id,
                        'correct'        => $isCorrect,
                        'correct_option' => $question->correct_option,
                    ];
                }

                if ($score >= ($num_qusetion / 2)) {
                    $passing_rate = (int) ($score * 100) / $num_qusetion;

                    if ($user && ! ($user->tests()->where('test_id', $test_id)->exists())) {
                        // استرجاع المادة الصحيحة المرتبطة بالمستخدم
                        $material = $user->materials()->where('material_id', $material_id)->first();

                        if ($material) {
                            $old_rate = $material->pivot->rate;
                            $rate     = (int) ($passing_rate * $test->returned_cost) / 100;
                            $new_rate = $old_rate + $rate;
                            $user->tests()->attach($test_id, ['passing_rate' => $passing_rate]);
                            $user->materials()->syncWithoutDetaching([$material_id => ['rate' => $new_rate]]);

                            return response()->json([
                                'new_rate'     => round($rate),
                                'passing_rate' => $passing_rate,
                                'results'      => $results,
                            ]);
                        }
                    }
                    return response()->json([
                        'passing_rate' => $passing_rate,
                        'results'      => $results,
                    ]);
                }

                return $this->successResponse('الرجاء اعادة الاختبار لان عدد الاجابات الصحيحة غير كافي لتجاوز الاختبار');
            }
            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
