<?php

use App\Filament\Widgets\LessonContent;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CoursesController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SummeryController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\VideoController;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/sections',[SectionController::class, 'index']);
Route::get('section/{id}/materials',[MaterialController::class, 'getMaterials']);//->middleware('auth:sanctum');
Route::get('section/material/lessons/{id}',[LessonController::class, 'getLessons']);
Route::get('section/material/lesson/videos/{id}',[VideoController::class, 'getVideos']);
Route::get('section/material/lesson/summeries/{id}',[SummeryController::class, 'getSummeries']);



Route::get('section/material/lesson/{lesson_id}/summery/{summery_id}',[SummeryController::class, 'show']);
Route::get('section/material/lesson/{lesson_id}/video/{video_id}',[VideoController::class, 'show']);


Route::get('section/material/lesson/tests/{id}',[TestController::class, 'getTests']);
Route::get('section/material/lesson/courses/{id}',[CoursesController::class, 'getCourses']);
Route::get('section/material/lesson/test/{id}/questions',[QuestionController::class,'getQuestions']);
Route::post('section/material/lesson/test/{id}/questions/asnwer',[QuestionController::class,'correctAsnwer']);
Route::post('video/store',[VideoController::class,'store']);
Route::get('summery/{id}/download',[SummeryController::class,'download']);

