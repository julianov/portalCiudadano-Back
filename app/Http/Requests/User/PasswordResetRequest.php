<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
			'token' => 'required',
			'password' => 'required|string|min:8'
	
		];
	}

	public function messages()
	{
		return [
			'token.required' => 'El token es requerido',
			'new_password.required' => 'La nueva contraseña es requerida',
		];
	}
}
