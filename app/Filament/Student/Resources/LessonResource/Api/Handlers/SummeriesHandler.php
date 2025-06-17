<?php

namespace App\Filament\Student\Resources\LessonResource\Api\Handlers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\LessonResource;
use App\Filament\Student\Resources\LessonResource\Api\Transformers\LessonTransformer;

class SummeriesHandler extends Handlers
{
    public static string | null $uri = '/{id}/summeries';
    public static string | null $resource = LessonResource::class;
    public static bool $public = true;


    /**
     * Show Lesson
     *
     * @param Request $request
     * @return LessonTransformer
     */
    public static function getMethod()
    {
        return Handlers::GET;
    }
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        $summeries = $query->summeries()
            ->get()
            ->map(fn($summery) => [
                'id' => $summery->id,
                'name' => $summery->name,
                'file' => $summery->file,
            ]);
        return $summeries;
    }
}
