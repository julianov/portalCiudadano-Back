<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthGetTokenAutenticarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public static function rules(): array
    {
        return [
            "cuil" => "required|min:11|max:11",
            "code" => "required|string",
        ];
    }
}
