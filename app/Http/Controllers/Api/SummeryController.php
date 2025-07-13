<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\Summery;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SummeryController extends Controller
{
    use ApiResponseTrait;

    public function show($lesson_id, $summery_id)
    {
        try {

            $lesson = Lesson::find($lesson_id);
            if (!$lesson) {
                return $this->errorResponse('غير متوفر ملخص لهذا الدرس', 404);
            }
            $user = Auth::guard(name: 'sanctum')->user();
            //زائر عادي والدرس مفتوح للكل
            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {
                $file_summery = Summery::find($summery_id);
                $url_file = $file_summery->file;
                $file_url = asset('storage/' . $url_file);

                return $this->successResponse(['file_url' => $file_url]);
            }

            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


    public function getSummeries($id)
    {
        try {

            $lesson = Lesson::find($id);
            if (!$lesson) {
                return $this->errorResponse('غير متوفر ملخص لهذا الدرس', 404);
            }

            $summeries =  $lesson->summeries()->get()
                ->map(fn($summery) => [
                    'id' => $summery->id,
                    'name' => $summery->name,
                ]);

            return $this->successResponse($summeries);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
