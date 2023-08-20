<?php

namespace App\Http\Requests\FormData;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'procedure_data_id' => 'required|int',
            'form_unit_code' => 'required|string',
            'form_data' => 'required',
            'attachments' => 'nullable',
        ];
    }
}
