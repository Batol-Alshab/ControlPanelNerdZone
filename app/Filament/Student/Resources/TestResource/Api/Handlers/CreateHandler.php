<?php
namespace App\Filament\Student\Resources\TestResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\TestResource;
use App\Filament\Student\Resources\TestResource\Api\Requests\CreateTestRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TestResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Test
     *
     * @param CreateTestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateTestRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}