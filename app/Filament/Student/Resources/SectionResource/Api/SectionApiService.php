<?php
namespace App\Filament\Student\Resources\SectionResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use PHPUnit\Event\Telemetry\Duration;
use App\Filament\Student\Resources\SectionResource;

class SectionApiService extends ApiService
{
    protected static string | null $resource = SectionResource::class;

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
    public static function customRoute(Router $router)
    {
        $router->get('materials', [static::class, 'materialHandler']);
    }
    public function materialHandler()
    {
        return "ok";
    }

}
