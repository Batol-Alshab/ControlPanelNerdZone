<?php
namespace App\Filament\Student\Resources\InquiryResource\Api\Handlers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Student\Resources\InquiryResource;
use App\Filament\Student\Resources\InquiryResource\Api\Transformers\InquiryTransformer;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = InquiryResource::class;
    // public static bool $public = true;

    public static array $middleware = [
        'auth:api',
        // Authenticate::class,
    ];

    /**
     * List of Inquiry
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function handler()
    {
        return auth()->user();
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for($query)
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return InquiryTransformer::collection($query);
    }
}
