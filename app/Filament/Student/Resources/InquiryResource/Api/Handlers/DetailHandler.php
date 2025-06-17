<?php

namespace App\Filament\Student\Resources\InquiryResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\InquiryResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Student\Resources\InquiryResource\Api\Transformers\InquiryTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = InquiryResource::class;


    /**
     * Show Inquiry
     *
     * @param Request $request
     * @return InquiryTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new InquiryTransformer($query);
    }
}
