<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
			'password' => 'required|string|min:8'
		];
	}

	public function messages()
	{
		return [
			'cuil.required' => 'El campo CUIL es obligatorio',
			'cuil.min' => 'El campo CUIL debe tener 11 caracteres',
			'cuil.max' => 'El campo CUIL debe tener 11 caracteres',
			'password.required' => 'El campo Contraseña es obligatorio',
		];
	}
}
