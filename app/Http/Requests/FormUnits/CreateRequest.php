<?php

namespace App\Http\Requests\FormUnits;

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
            'code' => 'required|string|min:1',
            'title' => 'required|string|min:1',
            'subtitle' => 'nullable|string|min:1',
            'description' => 'nullable|string|min:1',
            'keywords' => 'required|string|min:1',
            'status' => 'required|string|min:1',
            'elements' => 'required|min:1',
        ];
    }
}
