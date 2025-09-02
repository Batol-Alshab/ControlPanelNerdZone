<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponseTrait;
    public function getProfile()
    {try {
        $user = Auth::user();
        if (! $user) {
            return $this->unauthenticated();
        }
        $user->makeHidden('id');
        return $this->successResponse($user);

    } catch (\Exception $e) {

        return $this->errorResponse($e->getMessage());
    }}

    public function changePassword(Request $request)
    {
        try {
            $user = Auth::user();
            if (! $user) {
                return $this->unauthenticated();
            }
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|string|min:6|confirmed',
            ]);
            if (! Hash::check($request->input('current_password'), $user->password)) {
                return $this->errorResponse('كلمة المرور الحالية غير صحيحة .');
            }
            if (Hash::check($request->input('new_password'), $user->password)) {
                return $this->errorResponse('ادخل كلمة مرور جديدة');
            }
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            $user->tokens()->delete();

            return $this->successResponse('  تم تغيير كلمة المرور بنجاح قم بتسجيل الدخول ');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
