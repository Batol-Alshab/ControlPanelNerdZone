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
        $countQuestion = count($request->data) / 2;
        $solution = [];
        $cnt = 0;
        foreach ($request->data as $d) {
            $question = Question::findorfail($d['qustion_id']);
            if ($question->correct_option == $d['answer']) {
                $solution[$question->id] = 0;
                $d['answer'] =0;
                $cnt += 1;
            } else
                $solution[$question->id] = $question->correct_option;
                $d['answer']= $question->correct_option;
        }
        if ($cnt < $countQuestion) {
            return $this->successResponse(" الرجاء اعادة الاختبار لانك لم تتجاوز الحد الادنى من الاجابات الصحيحية");
        } else {
            return $this->successResponse($solution);
        }
        return $solution;
    }
}
