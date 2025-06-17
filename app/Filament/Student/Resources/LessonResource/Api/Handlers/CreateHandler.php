<?php
namespace App\Filament\Student\Resources\LessonResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\LessonResource;
use App\Filament\Student\Resources\LessonResource\Api\Requests\CreateLessonRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = LessonResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Lesson
     *
     * @param CreateLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateLessonRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}