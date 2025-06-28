<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Models\Lesson;
use App\Traits\OctetStream;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{ use ApiResponseTrait;
    use OctetStream;

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'video' => 'required|file|mimetypes:video/mp4,video/x-matroska',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $fileInfo = $this->storeToStorage($request, 'video', 'courses');

        $video = Video::create([
            'name' => $request->name,
            'video' => $fileInfo['path'], // saved relative path
            'lesson_id' => $request->lesson_id,
        ]);
    //    return $video;
        return response()->json(['message' => 'Video uploaded successfully', 'data' => $video]);
    }

    public function download($id)
    {
        $video = Video::findOrFail($id);

        return $this->returnFromStorageAsOctet($video->video);
    }


    public function getVideos($id){
        $videos=Lesson::find($id)->videos()->get()
        ->map(fn($video) => [
            'name' => $video->name,
            'video' => $video->video,
            ]);
        return $this->successResponse($videos);
    }
}
