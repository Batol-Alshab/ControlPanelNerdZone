<?php

namespace App\Http\Controllers\Api;

use App\Models\Summery;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    use ApiResponseTrait;

    public function addToFavourite(string $id, string $type)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return $this->unauthorized("لا يمكنك الإضافة للمفضلة لأنك غير مسجل دخول");
        }

        $modelClass = match ($type) {
            'summery' => \App\Models\Summery::class,
            'video' => \App\Models\Video::class,
            default => null,
        };

        if (!$modelClass) {
            return $this->errorResponse('نوع غير مدعوم');
        }

        $model = $modelClass::findOrFail($id);

        $fav = Favorite::where([
            'user_id' => $user->id,
            'favoritable_type' => $modelClass,
            'favoritable_id' => $model->id,
        ])->first();
        if ($fav)
            return $this->errorResponse("تمت اضافتة للمفضلة مسبقا");
        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => $modelClass,
            'favoritable_id' => $model->id,
        ]);

        return $this->successResponse('تمت الإضافة للمفضلة');
    }




    public function deleteFromFavourite(string $id, string $type)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return $this->unauthorized();
        }

        $modelClass = match ($type) {
            'summery' => \App\Models\Summery::class,
            'video' => \App\Models\Video::class,
            default => null,
        };

        if (!$modelClass) {
            return $this->errorResponse('نوع غير مدعوم');
        }

        $model = $modelClass::findOrFail($id);

        $fav = Favorite::where([
            'user_id' => $user->id,
            'favoritable_type' => $modelClass,
            'favoritable_id' => $model->id,
        ])->first();
        if (!$fav) {
            return $this->notFound();
        }
        $fav->delete();


        return $this->successResponse("تمت الازالة من المفضلة بنجاح");
    }



    public function getSummeryFavourite()
    {
        $user = Auth::guard('sanctum')->user();
        $summery = Favorite::where([
            'user_id' => $user->id,
            'favoritable_type' => \App\Models\Summery::class,
        ])->with('favoritable')->get();
        $summeries = $user->favorites()->get();
        $data = [];
        foreach ($summeries as $s) {
            $data = $s->favoritable;
        }
        return $this->successResponse($data);
    }

    public function getVideoFavourite()
    {
        $user = Auth::guard('sanctum')->user();
        $summery = Favorite::where([
            'user_id' => $user->id,
            'favoritable_type' => \App\Models\Video::class,
        ])->with('favoritable')->get();
        $videos = $user->favorites()->get();
        $data = [];
        foreach ($videos as $v) {
            $data = $v->favoritable;
        }
        return $this->successResponse($data);
    }
}
