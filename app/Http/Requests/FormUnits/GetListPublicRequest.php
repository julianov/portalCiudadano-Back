<?php

namespace App\Http\Requests\FormUnits;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Pagination;

class GetListPublicRequest extends FormRequest {
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return Pagination::rules();
    }
}
