<?php

namespace App\Http\Requests\FormUnits;

class GetByPkRequest extends CreateRequest
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
            // 'elements.*.type' => 'required|string|min:1',
            // 'elements.*.properties' => 'required|mixed',
            // 'elements.*.additionalValidations' => 'required|array',
            // 'elements.*.additionalValidations.*' => 'required|string|min:1',
            // 'elements.*.name' => 'required|string|min:1',
            // 'elements.*.value' => 'required',
            // 'status' => 'required|string|min:1'
            // 'code' => 'required|string|min:1',
            // 'fields' => 'required|array|min:1',
            // 'fields.*.properties' => 'required|array|max:1',
            // 'fields.*.properties.type' => 'required|string',
            // 'fields.*.validators.type' => 'required|string',
        ];
    }
}
