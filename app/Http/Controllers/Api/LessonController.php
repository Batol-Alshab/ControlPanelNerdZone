<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class LessonController extends Controller
{ use ApiResponseTrait;
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
    public function getLessons($id){
        try{
            $materail=Material::find($id);
            if(! $materail)
            {
                return $this->errorResponse('غير متوفر دروس لهذه المادة', 404);
            }
            $lessons = $materail->lessons()->get()
                ->map(fn($lesson) => [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                ]);

            return $this->successResponse($lessons);

        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
