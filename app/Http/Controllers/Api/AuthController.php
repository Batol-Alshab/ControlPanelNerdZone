<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    function Register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|regex:/^[\p{Arabic}a-zA-Z]+[\p{Arabic}a-zA-Z0-9\s]*$/u|string',
                'password' => 'required|confirmed|min:6',
                'email' => 'required|string|email:filter|max:100|unique:users,email',
                'sex' => 'required',
                'city' => 'string|required',
                'section_id' => 'required|exists:sections,id'
            ]);


            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'sex' => $request->sex,
                'city' => $request->city,
                'section_id' => $request->section_id,
            ]);
            if (! $user) {
                return $this->errorResponse('تعذر انشاء الحساب');
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->assignRole('student');
            $materials = Material::where('section_id', $request->section_id)->pluck('id');
            foreach ($materials as $m) {
                $user->materials()->attach($m,['rate' => 10]);
            }
            return $this->successResponse(null, "تم تسجيل الدخول بنجاح", 200)->header('Authorization',  $token);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!Auth::attempt($credentials)) {
                return $this->errorResponse("الايميل او كلمة المرور غير صحيحة", 401);
            }

            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(null, "تم تسجيل الدخول بنجاح", 200)->header('Authorization', $token);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken();

            return $this->successResponse('تم تسجيل الخروج بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
