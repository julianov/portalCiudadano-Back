<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewNotificationRequest extends FormRequest
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
            "message_title" => "required|string",
            "message_body" => "required|string",
            "attachment" => "nullable",
            "notification_date_from" => "required|max:50|string|date_format:d/m/Y",
            "notification_date_to" => "required|max:50|string|date_format:d/m/Y",
            "send_by_email" =>  [
                'required',
                Rule::in(['true', 'false']),
            ],
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
            "message_title" => 'El campo message_title es requerida',
            "message_body" => 'El campo message_body es requerida',
            "notification_date_from" => 'El campo notification_date_from es requerida',
            "notification_date_from.date_format" => 'El campo notification_date_from debe ser formato dd/mm/yy',
            "notification_date_to" => 'El campo notificaion_date_to es requerida',
            "notification_date_to.date_format" => 'El campo notification_date_to debe ser formato dd/mm/yy',
            "send_by_email" =>  'El campo send_by_email es requerida',
		];
	}
}