<?php
namespace App\Filament\Student\Resources\TestResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use App\Filament\Student\Resources\TestResource;


class TestApiService extends ApiService
{
    protected static string | null $resource = TestResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
        ];

    }
}
