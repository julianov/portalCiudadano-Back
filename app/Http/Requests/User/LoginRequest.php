<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
			'cuil' => 'required|min:11|max:11',
			'password' => 'required',
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
