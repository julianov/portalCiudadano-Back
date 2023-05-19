<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class checkNotificationScope extends FormRequest
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
            // "token" => "required|string",
            "recipients" => [
                'required',
                'string',
                Rule::in(['citizen', 'actor', 'both']), // Verificar que el valor sea uno de los permitidos
            ], 
            "age_from" => "required|numeric",
            "age_to" => "required|numeric",
            "department_id" => "required|numeric",
            "locality_id" => "required|numeric",
            
        ];
	}

	public function messages()
	{
		return [
			"recipients" => 'El campo recipients es requerida',
            "age_from" => 'El campo age_from es requerida',
            "age_to" => 'El campo age_to es requerida',
            "department_id" => 'El campo department_id es requerida',
            "locality_id" => 'El campo locality_id es requerida',
		];
	}
}

