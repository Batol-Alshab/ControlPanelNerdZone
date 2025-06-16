<?php
namespace App\Filament\Student\Resources\UserResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use App\Filament\Student\Resources\UserResource;


class UserApiService extends ApiService
{
    protected static string | null $resource = UserResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }

    
}
