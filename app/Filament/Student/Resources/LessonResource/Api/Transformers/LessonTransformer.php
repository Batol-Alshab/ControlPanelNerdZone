<?php
namespace App\Filament\Student\Resources\LessonResource\Api\Transformers;

use App\Models\Lesson;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Lesson $resource
 */
class LessonTransformer extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
