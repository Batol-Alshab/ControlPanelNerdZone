<?php
namespace App\Filament\Student\Resources\SectionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\SectionResource;
use App\Filament\Student\Resources\SectionResource\Api\Requests\CreateSectionRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = SectionResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Section
     *
     * @param CreateSectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateSectionRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}