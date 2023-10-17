<?php

namespace App\Http\Requests\FormUnits;

use Illuminate\Foundation\Http\FormRequest;

class GetListBySearchRequest extends FormRequest
{
    public function authorize()
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
