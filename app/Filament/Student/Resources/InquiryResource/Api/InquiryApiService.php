<?php
namespace App\Filament\Student\Resources\InquiryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Student\Resources\InquiryResource;
use Illuminate\Routing\Router;


class InquiryApiService extends ApiService
{
    protected static string | null $resource = InquiryResource::class;

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
