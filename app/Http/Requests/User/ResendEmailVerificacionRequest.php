<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ResendEmailVerificacionRequest extends FormRequest
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

            'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
			'captcha' => 'required|string|min:8'
		];
	}
	/**
	 * Get the error messages for the defined validation rules.
	 */
	public function messages(): array
	{
		return [
            'cuil.required' => 'El campo CUIL es obligatorio',
			'cuil.min' => 'El campo CUIL debe tener 11 caracteres',
			'cuil.max' => 'El campo CUIL debe tener 11 caracteres',
			'captcha.required' => 'El campo Catcha es obligatorio',		];
	}
}
