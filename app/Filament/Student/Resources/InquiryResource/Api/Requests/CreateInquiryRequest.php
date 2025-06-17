<?php

namespace App\Filament\Student\Resources\InquiryResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInquiryRequest extends FormRequest
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
			'inquiry' => 'required|string',
			// 'user_id' => 'required',
			// 'status' => 'required',
			// 'inquiryable_type' => 'required',
			// 'inquiryable_id' => 'required',
			// 'deleted_at' => 'required'
		];
    }
}
