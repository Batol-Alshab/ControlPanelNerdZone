<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Models\Lesson;
use App\Traits\OctetStream;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    use ApiResponseTrait;
    use OctetStream;

    public function show($lesson_id, $video_id)
    {
        try {

            $lesson = Lesson::find($lesson_id);
            if (!$lesson) {
                return $this->errorResponse('غير متوفر ملخص لهذا الدرس', 404);
            }
            $user = Auth::guard(name: 'sanctum')->user();
            //زائر عادي والدرس مفتوح للكل
            if ($lesson->cost == 0 || ($lesson->cost > 0 && $user && $lesson->users()->where('user_id', $user->id)->exists())) {
                $video_file = Video::find($video_id);
                $video_url = $video_file->video;
                // return $video_url;
                $video_url = asset('storage/' . $video_url);

                return $this->successResponse(['video_url' => $video_url]);
            }

            return $this->successResponse('مقفول');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function getVideos($id)
    {
        $videos = Lesson::find($id)->videos()->get()
            ->map(fn($video) => [
                'id' => $video->id,
                'name' => $video->name,
            ]);
        return $this->successResponse($videos);
    }
}
