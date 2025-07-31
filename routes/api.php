<?php

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Widgets\LessonContent;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\CoursesController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SummeryController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\TaskController;
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
Route::get('lesson/{id}/open',[LessonController::class, 'openLesson']);
Route::get('section/material/lesson/videos/{id}',[VideoController::class, 'getVideos']);
Route::get('section/material/lesson/{lesson_id}/video/{video_id}',[VideoController::class, 'show']);
Route::get('section/material/lesson/summeries/{id}',[SummeryController::class, 'getSummeries']);
Route::get('section/material/lesson/{lesson_id}/summery/{summery_id}',[SummeryController::class, 'show']);
Route::get('section/material/lesson/tests/{id}',action: [TestController::class, 'getTests']);
Route::get('section/material/lesson/courses/{id}',action: [CoursesController::class, 'getCourses']);
Route::get('section/material/lesson/{lesson_id}/course/{course_id}',[CoursesController::class, 'show']);
Route::get('section/material/lesson/{lesson_id}/test/{test_id}/questions',[QuestionController::class,'getQuestions']);
Route::post('section/material/lesson/{lesson_id}/test/{test_id}/questions/asnwer',[QuestionController::class,'correctAsnwer']);
Route::post('video/store',[VideoController::class,'store']);
Route::get('summery/{id}/download',[SummeryController::class,'download']);

Route::post('favourites/{type}/{id}', [FavouriteController::class, 'addToFavourite']);
Route::delete('favourites/{id}/{type}', [FavouriteController::class, 'deleteFromFavourite']);
Route::get('summery/favourites', [FavouriteController::class, 'getSummeryFavourite'])->middleware('auth:sanctum');
Route::get('video/favourites', [FavouriteController::class, 'getVideoFavourite'])->middleware('auth:sanctum');

Route::get('getTask',[TaskController::class,'getTask'])->middleware('auth:sanctum');
Route::get('getEndOrDoneTask',[TaskController::class,'getEndOrDoneTask'])->middleware('auth:sanctum');
Route::post('storeTask',[TaskController::class,'store'])->middleware('auth:sanctum');
Route::post('updateTask/{id}',[TaskController::class,'update'])->middleware('auth:sanctum');
Route::get('deleteTask/{id}',[TaskController::class,'destroy'])->middleware('auth:sanctum');
Route::get('testsend',[TaskController::class,'sendMessage']);