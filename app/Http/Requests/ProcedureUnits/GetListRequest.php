<?php

namespace App\Http\Requests\ProcedureUnits;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Pagination;

class GetListRequest extends FormRequest
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
        return Pagination::rules();
    }
}
