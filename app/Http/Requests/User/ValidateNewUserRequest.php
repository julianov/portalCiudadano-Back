<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ValidateNewUserRequest extends FormRequest
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
			'confirmation_code' => 'required|min:6|max:6',
		];
	}

	public function messages()
	{
		return [
			'cuil.required' => 'El campo CUIL es obligatorio',
			'cuil.min' => 'El campo CUIL debe tener 11 caracteres',
			'cuil.max' => 'El campo CUIL debe tener 11 caracteres',
			'confirmation_code.required' => 'El campo Código de confirmación es obligatorio',
			'confirmation_code.min' => 'El campo Código de confirmación debe tener 6 caracteres',
			'confirmation_code.max' => 'El campo Código de confirmación debe tener 6 caracteres',
		];
	}
}
