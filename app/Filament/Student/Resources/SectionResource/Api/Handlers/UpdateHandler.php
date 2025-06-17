<?php
namespace App\Filament\Student\Resources\SectionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\SectionResource;
use App\Filament\Student\Resources\SectionResource\Api\Requests\UpdateSectionRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = SectionResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Section
     *
     * @param UpdateSectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateSectionRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}