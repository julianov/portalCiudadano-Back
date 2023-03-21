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
			'cuil' => 'required',
			'birthday' => 'required',
			'cellphone_number' => 'required',
			'department_id' => 'required',
			'locality_id' => 'required',
			'address_street' => 'required',
			'address_number' => 'required',
			'apartment' => 'nullable',
		];
	}

	public function messages()
	{
		return [
			'cuil.required' => 'El CUIL es requerido',
			'birthday.required' => 'La fecha de nacimiento es requerida',
			'cellphone_number.required' => 'El número de celular es requerido',
			'department_id.required' => 'El departamento es requerido',
			'locality_id.required' => 'La localidad es requerida',
			'address_street.required' => 'La calle es requerida',
			'address_number.required' => 'El número de calle es requerido',
		];
	}
}

