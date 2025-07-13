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
                // $loadRelationship = Lesson::has('users');

                // $user_id = $user->id;
                // $lessons = $user->lessons->map(fn($lesson) => [
                //     'id' => $lesson->id,
                //     'name' => $lesson->name,
                //     'is_open' => $lesson->pivot,
                // ]);
                 $lessons = $materail->lessons()->get()
                    ->map(fn($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'cost' => $lesson->cost,
                        'is_open' => $lesson->users()->where('user_id',$user->id)->first() ? 1 : 0
                    ]);
                    // $lessons->users();//->where('user_id',$user->id);
                // ])->when($loadRelationship, function ($query) use ($user_id) {
                //     return $query->with( ['lesson' => function ($query) use ($user_id) {
                //         $query->where('user_id', $user_id); // Assuming you want to filter by user_id
                //     }]);
                // });
                return $this->successResponse(data: $lessons);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
