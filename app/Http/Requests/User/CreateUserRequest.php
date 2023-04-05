<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CreateUserRequest extends FormRequest
{
	use ValidatesRequests;

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
	public function rules(): array
	{
		return [
			'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
			'prs_id' => 'required',
			'nombre' => 'required|string|max:50',
			'apellido' => 'required|string|max:50',
			'email' => 'required|max:70',
			'password' => 'required|string|min:8'
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 * @return array<string, mixed>
	 *     The error messages.
	 */
	public function messages(): array
	{
		return [
			'cuil.required' => 'El campo CUIL es obligatorio',
			'prs_id.required' => 'El campo prs_id es obligatorio',
			'nombre.required' => 'El campo Nombre es obligatorio',
			'apellido.required' => 'El campo Apellido es obligatorio',
			'email.required' => 'El campo Email es obligatorio',
			'password.required' => 'El campo Contraseña es obligatorio',
		];
	}


}
