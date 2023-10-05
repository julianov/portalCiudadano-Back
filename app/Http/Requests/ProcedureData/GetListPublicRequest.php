<?php

namespace App\Http\Requests\ProcedureData;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Pagination;

class GetListPublicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Pagination::rules();
    }
}
