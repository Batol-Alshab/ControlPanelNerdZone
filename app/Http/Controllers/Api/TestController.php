<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;

class TestController extends Controller
{
    use ApiResponseTrait;


    public function getTests($id)
    {
        $tests = Lesson::find($id)->tests()->where('is_complete', 1)->get()
            ->map(fn($test) => [
                'id' => $test->id,
                'name' => $test->name,
            ]);
        return $this->successResponse($tests);
    }
}
