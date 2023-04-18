<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class validateFaceToFaceCitizenRequest extends FormRequest
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
			"cuil_citizen" => "required|numeric|regex:/^[0-9]{11}$/",
            "token" => "required|string",
            'name' => 'required|string|max:50',
			'last_name' => 'required|string|max:50',
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
		
            "cuil_citizen" => 'El campo cuil_citizen es requerido',
            "token" => 'El campo token es requerido',
            'name' => 'El campo name es requerido',
			'last_name' => 'El campo last_name es requerido',
            'birthday' => 'El campo birthday es requerido',
			'cellphone_number' => 'El campo cellphone_number es requerido',
			'department_id' => 'El campo department_id es requerido',
			'locality_id' => 'El campo locality_id es requerido',
			'address_street' => 'El campo address_street es requerido',
			'address_number' => 'El campo address_number es requerido',

		];
	}
}

