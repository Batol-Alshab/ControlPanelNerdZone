<?php

namespace App\Filament\Student\Resources\LessonResource\Api\Handlers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\LessonResource;
use App\Filament\Student\Resources\LessonResource\Api\Transformers\LessonTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = LessonResource::class;
    public static bool $public = true;


    /**
     * Show Lesson
     *
     * @param Request $request
     * @return LessonTransformer
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

        return new LessonTransformer($query);
    }
}
