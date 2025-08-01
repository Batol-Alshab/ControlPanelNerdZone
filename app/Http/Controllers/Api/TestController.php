<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\UserTest;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    use ApiResponseTrait;


    public function getTests($id)
    {
        try {
            $lesson = Lesson::find($id);
            $user = Auth::guard(name: 'sanctum')->user();
            // auth('sanctum')->user();
            $tests = $lesson->tests()->where('is_complete', 1)->get();
            if (!$user) {

                $tests = $tests->map(fn($test) => [
                    'id' => $test->id,
                    'name' => $test->name,
                    'returned_cost' => $test->returned_cost,
                ]);
                return $this->successResponse($tests);
            } else {
                $user_id=$user->id;
                $tests = $tests->map(fn($test) => [
                    'id' => $test->id,
                    'name' => $test->name,
                    'returned_cost' => $test->returned_cost,
                    'passing_rate' => UserTest::where('user_id',$user_id)->where('test_id',$test->id)->first('passing_rate') ,
                ]);
            //    return  UserTest::where('user_id',$user->id)->where('test_id',1)->get();
                // return $user->tests()->where('test_id', 1)->get();
                return $this->successResponse($tests);

                // $tests->users();
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
