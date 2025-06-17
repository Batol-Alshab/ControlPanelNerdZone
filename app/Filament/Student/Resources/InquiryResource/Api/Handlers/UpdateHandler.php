<?php
namespace App\Filament\Student\Resources\InquiryResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\InquiryResource;
use App\Filament\Student\Resources\InquiryResource\Api\Requests\UpdateInquiryRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = InquiryResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Inquiry
     *
     * @param UpdateInquiryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateInquiryRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}