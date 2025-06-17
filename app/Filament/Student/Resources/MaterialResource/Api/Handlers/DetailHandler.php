<?php

namespace App\Filament\Student\Resources\MaterialResource\Api\Handlers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\MaterialResource;
use App\Filament\Student\Resources\MaterialResource\Api\Transformers\MaterialTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = MaterialResource::class;
    public static bool $public = true;

    /**
     * Show Material
     *
     * @param Request $request
     * @return MaterialTransformer
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

        $lessons = $query->lessons()->get()
        ->map(fn($lesson) => [
                'id' => $lesson->id,
                'name' => $lesson->name,
            ]);
        return  $lessons;
    }
}
