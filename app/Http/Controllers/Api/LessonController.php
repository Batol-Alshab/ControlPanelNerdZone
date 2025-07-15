<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Lesson;
use App\Models\lessons;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getLessons($id)
    {
        try {
            $materail = Material::find($id);
            if (! $materail) {
                return $this->errorResponse('غير متوفر دروس لهذه المادة', 404);
            }
            $user = Auth::guard(name: 'sanctum')->user();

            if (! $user) {
                $lessons = $materail->lessons()->get()
                    ->map(fn($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'cost' => $lesson->cost
                    ]);

                return $this->successResponse($lessons);
            } else {
                $lessons = $materail->lessons()->get()
                    ->map(fn($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'cost' => $lesson->cost,
                        'is_open' => $lesson->users()->where('user_id', $user->id)->first() ? 1 : 0
                    ]);

                return $this->successResponse($lessons);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function openLesson($id)
    {
        try {
            $lesson = Lesson::find($id);
            if (!$lesson) {
                return $this->errorResponse('غير متوفر الدرس', 404);
            }
            $user = Auth::guard(name: 'sanctum')->user();
            if (!$user) {
                return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
            }
            if ($user->lessons()->where('lesson_id', $lesson->id)->exists() || $lesson->cost == 0) {
                return $this->errorResponse('مفتوح مسبقا');
            }
            $material_id = $lesson->material->id;
            $user_material = $user->materials->where('id', $material_id)->first();
            $rate_user_material = $user_material->pivot->rate;

            if ($rate_user_material < $lesson->cost) {
                return $this->errorResponse('عدد النقاط غير كافي لفتح الدرس',);
            }
            $new_rate = $rate_user_material -  $lesson->cost;
            $user_material->pivot->update([
                'rate' => $new_rate
            ]);
            $user->lessons()->attach($lesson->id);
            return $this->successResponse($new_rate);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
