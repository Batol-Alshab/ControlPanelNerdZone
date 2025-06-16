<?php
namespace App\Filament\Student\Resources\UserResource\Api\Transformers;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserTransformer extends JsonResource
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
            'name' =>$this->name,
            'email' =>$this->email,
            'city' => $this->city,
            'sex' => $this->sex,
            'section_id' => $this->section_id,
            'rate' => $this->rate,
        ];
    }
}
