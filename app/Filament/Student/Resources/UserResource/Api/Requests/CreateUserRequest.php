<?php

namespace App\Filament\Student\Resources\UserResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'name' => 'required',
			'city' => 'required',
			'sex' => 'required',
			'email' => 'required',
			'password' => 'required',
			'section_id' => 'required',
			'rate' => 'required',
			'email_verified_at' => 'required',
			'remember_token' => 'required'
		];
    }
}
