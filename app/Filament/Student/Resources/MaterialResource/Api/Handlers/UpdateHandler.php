<?php
namespace App\Filament\Student\Resources\MaterialResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\MaterialResource;
use App\Filament\Student\Resources\MaterialResource\Api\Requests\UpdateMaterialRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = MaterialResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Material
     *
     * @param UpdateMaterialRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateMaterialRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}