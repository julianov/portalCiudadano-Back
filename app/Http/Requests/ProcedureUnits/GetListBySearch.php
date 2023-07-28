<?php

namespace App\Http\Requests\ProcedureUnits;

use Illuminate\Foundation\Http\FormRequest;

class GetListBySearchRequest extends FormRequest
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

    public function rules()
    {
        return [];
    }
}
