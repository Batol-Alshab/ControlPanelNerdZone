<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Inquiry;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AnswerController extends Controller
{use ApiResponseTrait;
    public function storeAnswer(Request $request)
    {
        try {
            $validated = $request->validate([
                'inquiry_id'     => 'required|exists:inquiries,id',
                'answer_content' => 'required|string|min:10',
            ]);

            // Find the inquiry to attach the answer to.
            $inquiry = Inquiry::findOrFail($validated['inquiry_id']);
            // Check if an answer for this inquiry already exists.
            if ($inquiry->answer()->exists()) {
                return $this->errorResponse('هذا الاستفسار لديه إجابة بالفعل.', 409); // 409 Conflict
            }
            // Create the new answer record.
            $answer = Answer::create([
                'inquiry_id'     => $inquiry->id,
                'answer_content' => $validated['answer_content'],
            ]);
            $inquiry->update(['status' => 'Complete Answer']);
            return $this->successResponse('تم إرسال الإجابة بنجاح', ['answer' => $answer], 201);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('الاستفسار المطلوب غير موجود', 404);
        }
    }
    public function deleteAnswer(string $id)
    {
        try {
            $answer  = Answer::findOrFail($id);
            $inquiry = $answer->inquiry;
            $answer->delete();

            $inquiry->update(['status' => 'No Answer']);

            return $this->successResponse('تم حذف الإجابة بنجاح');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('الإجابة المطلوبة غير موجودة', 404);
        }
    }}
