<?php
namespace App\Filament\Student\Resources\MaterialResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use App\Filament\Student\Resources\MaterialResource;


class MaterialApiService extends ApiService
{
    protected static string | null $resource = MaterialResource::class;
    public static bool $public = true;
    
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
