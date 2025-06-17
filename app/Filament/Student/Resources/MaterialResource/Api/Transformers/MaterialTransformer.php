<?php
namespace App\Filament\Student\Resources\MaterialResource\Api\Transformers;

use App\Models\Material;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Material $resource
 */
class MaterialTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ,
            'name' => $this->name ,
            'image' => $this->image ,
        ];
    }
}
