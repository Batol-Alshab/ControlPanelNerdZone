<?php
namespace App\Filament\Student\Resources\InquiryResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Inquiry;

/**
 * @property Inquiry $resource
 */
class InquiryTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
