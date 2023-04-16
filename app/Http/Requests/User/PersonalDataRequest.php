<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PersonalDataRequest extends FormRequest
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
			'birthday' => 'required|max:50|string',
			'cellphone_number' => 'required|max:50|string',
			'department_id' => 'required|numeric',
			'locality_id' => 'required|numeric',
			'address_street' => 'required|max:50|string',
			'address_number' => 'required|numeric',
			'apartment' => 'nullable|max:50|string',
		];
	}

	public function messages()
	{
		return [
			'birthday.required' => 'La fecha de nacimiento es requerida',
			'cellphone_number.required' => 'El número de celular es requerido',
			'department_id.required' => 'El departamento es requerido',
			'locality_id.required' => 'La localidad es requerida',
			'address_street.required' => 'La calle es requerida',
			'address_number.required' => 'El número de calle es requerido',
		];
	}
}

