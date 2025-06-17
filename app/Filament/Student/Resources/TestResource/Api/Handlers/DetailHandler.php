<?php

namespace App\Filament\Student\Resources\TestResource\Api\Handlers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\TestResource;
use App\Filament\Student\Resources\TestResource\Api\Transformers\TestTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = TestResource::class;
    public static bool $public = true;


    /**
     * Show Test
     *
     * @param Request $request
     * @return TestTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
            ->where('is_complete', 1)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();
        $questions = $query->questions()
            ->get()
            ->map(fn($question) => [
                'content' => $question->content,
                'image' => $question->image,
                'option_1' => $question->option_1,
                'option_2' => $question->option_2,
                'option_3' => $question->option_3,
                'option_4' => $question->option_4,
            ]);

        return $questions;
    }
}
