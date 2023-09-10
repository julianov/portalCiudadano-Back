<?php

namespace App\Http\Requests\FormData;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentsRequest extends FormRequest
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
            'attachments' => 'required'
        ];
    }
}
