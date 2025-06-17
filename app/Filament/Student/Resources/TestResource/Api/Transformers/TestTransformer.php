<?php
namespace App\Filament\Student\Resources\TestResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Test;

/**
 * @property Test $resource
 */
class TestTransformer extends JsonResource
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
