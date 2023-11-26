<?php

namespace App\Http\Requests\ProcedureData;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAttachmentsRequest extends FormRequest
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
            'form_data_id' => 'required|int',
            'multimedia_id' => 'required|int'
        ];
    }
}