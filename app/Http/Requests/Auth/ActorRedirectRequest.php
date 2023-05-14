<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActorRedirectRequest extends FormRequest
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
			"dni" => "required|numeric|regex:/^[0-9]$/",
            "token" => "required|string",
            
		];
	}

	public function messages()
	{
		return [
		
            "dni" => 'El campo dni es requerido',
            "token" => 'El campo token es requerido',
            
		];
	}
}

