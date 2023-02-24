<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserEmailRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
            'token' => 'required'
		];
	}
	/**
	 * Get the error messages for the defined validation rules.
	 */
	public function messages(): array
	{
		return [
            'token.required' => 'El token es requerido',
		];
	}
}
