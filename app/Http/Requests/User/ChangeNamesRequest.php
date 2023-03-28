<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeNamesRequest extends FormRequest
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
            'name' => 'required',
            'last_name' => 'required'
		];
	}
	/**
	 * Get the error messages for the defined validation rules.
	 */
	public function messages(): array
	{
		return [
			'cuil.required' => 'El CUIL es requerido',
			'cuil.min' => 'El CUIL debe tener 11 caracteres',
			'cuil.max' => 'El CUIL debe tener 11 caracteres',
            'name.required' => 'El nuevo nombre es requerido',
            'last_name.required' => 'El nuevo apellido es requerido',

		];
	}
}