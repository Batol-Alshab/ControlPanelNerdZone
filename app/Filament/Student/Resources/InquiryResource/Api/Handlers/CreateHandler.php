<?php
namespace App\Filament\Student\Resources\InquiryResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Student\Resources\InquiryResource;
use App\Filament\Student\Resources\InquiryResource\Api\Requests\CreateInquiryRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = InquiryResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Inquiry
     *
     * @param CreateInquiryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateInquiryRequest $request)
    {
        
        // $model = new (static::getModel());

        // $model->fill($request->all());

        // $model->save();

        // return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
