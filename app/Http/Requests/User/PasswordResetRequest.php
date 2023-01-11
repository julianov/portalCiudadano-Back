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
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'cuil' => 'required',
			'new_password' => 'required',
			'verification_code' => 'required',
		];
	}

	public function messages()
	{
		return [
			'cuil.required' => 'El cuil es requerido',
			'new_password.required' => 'La nueva contraseña es requerida',
			'verification_code.required' => 'El código de verificación es requerido',
		];
	}
}
