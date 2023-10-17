<?php

namespace App\Http\Requests\ProcedureUnits;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Pagination;

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
        return array_merge(
            Pagination::rules(),
            [
                'keyword' => 'required|string|min:1',
            ]
        );
    }
}
