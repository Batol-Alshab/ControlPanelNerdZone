<?php
namespace App\Filament\Student\Resources\LessonResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use App\Filament\Student\Resources\LessonResource;


class LessonApiService extends ApiService
{
    protected static string | null $resource = LessonResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
            Handlers\SummeriesHandler::class,
            Handlers\CoursesHandler::class,
            Handlers\VideosHandler::class,
            Handlers\TestsHandler::class,
        ];

    }
}
