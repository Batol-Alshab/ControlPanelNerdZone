<?php
namespace App\Filament\Student\Resources\MaterialResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\MaterialResource;
use App\Filament\Student\Resources\MaterialResource\Api\Requests\CreateMaterialRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = MaterialResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Material
     *
     * @param CreateMaterialRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateMaterialRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}