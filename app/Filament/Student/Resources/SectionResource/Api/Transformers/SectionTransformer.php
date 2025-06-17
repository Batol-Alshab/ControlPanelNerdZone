<?php
namespace App\Filament\Student\Resources\SectionResource\Api\Transformers;

use App\Models\Section;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Section $resource
 */
class SectionTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return $this->resource->toArray();
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
