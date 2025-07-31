<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{ use ApiResponseTrait;
    
    public function show($lesson_id, $course_id)
    {
        try {

            $lesson = Lesson::find($lesson_id);
            if (!$lesson) {
                return $this->errorResponse('غير متوفر دورة لهذا الدرس', 404);
            }
            $user = Auth::guard(name: 'sanctum')->user();
            
            //زائر عادي والدرس مفتوح للكل
            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id',  $user->id)->exists())) {
                $course_file = Course::find($course_id);
                $course_url = $course_file->file;
                // return $video_url;
                $course_url = asset('storage/' . $course_url);

                return $this->successResponse(['video_url' => $course_url]);
            }

            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getCourses($id){
        $courses=Lesson::find($id)->courses()->get()
        ->map(fn($course) => [
            'id' => $course->id,
            'name' => $course->name,
            ]);
        return $this->successResponse($courses);
    }
}
