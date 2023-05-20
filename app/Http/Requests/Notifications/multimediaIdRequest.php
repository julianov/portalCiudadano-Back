<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class multimediaIdRequest extends FormRequest
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
            "multimedia_id" => "required|numeric",
		];
	}

	public function messages()
	{
		return [
		
            "multimedia_id" => 'El campo multimedia_id es requerido',
            
		];
	}
}

