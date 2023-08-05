<?php

namespace App\Http\Requests\ProcedureUnits;

use Illuminate\Foundation\Http\FormRequest;

class UpdateByPkRequest extends FormRequest
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
            'id' => 'required|integer|min:1',
            'title' => 'required|string|min:1|max:300',
            'description' => 'required|string|min:1',
            // 'secretary'=> 'required|string|min:1',
            'state' => 'required|string|min:1|max:100',
            'theme'=> 'required|string|min:1|max:100',
            'forms' => 'required|string|min:1',
            'attachments'=> 'required|string|min:1',
        ];
    }
}
