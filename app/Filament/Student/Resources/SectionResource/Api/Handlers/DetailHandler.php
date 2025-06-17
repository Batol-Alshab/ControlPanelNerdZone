<?php

namespace App\Filament\Student\Resources\SectionResource\Api\Handlers;

use App\Models\Material;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Student\Resources\SectionResource;
use App\Filament\Student\Resources\SectionResource\Api\Transformers\SectionTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = SectionResource::class;
    public static bool $public = true;

    /**
     * Show Section
     *
     * @param Request $request
     * @return SectionTransformer
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
        $materials = $query->materials()
            ->get()
            ->map(fn($material) => [
                'id' => $material->id,
                'name' => $material->name,
                'image' => $material->image,
            ]);
        // Material::where('section_id',$query->id)->first();

        return $materials;
        // new SectionTransformer($query);
    }
}
