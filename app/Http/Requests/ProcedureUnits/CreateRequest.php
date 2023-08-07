<?php

namespace App\Http\Requests\ProcedureUnits;

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

   /* protected function prepareForValidation()
    {
        $lowerCaseTags = $this->tags->foreach(function ($tag) {
            return strtolower($tag->trim());
        });
        $uniqueTags = array_unique($lowerCaseTags);

        $this->merge([ 'tags' => $uniqueTags ]);
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /*return [
            'name' => 'required|string|min:1|max:50',
            'title' => 'required|string|min:1|max:50',
            'subtitle' => 'required|string|min:1|max:100',
            'description' => 'required|string|min:1',
            'forms' => 'required|array|min:1',
            'forms.*' => 'required|integer|min:1',
            'tags' => 'array|min:0|max:100',
            'tags.*' => 'string|distinct|min:1',
            'actor_level' => 'required|integer|min:1|max:3',
            'citizen_level' => 'required|integer|min:1|max:3',
        ];*/
        return [
            'title' => 'required|string|min:1|max:300',
            'description' => 'required|string|min:1',
            'secretary'=> 'required|string|min:1',
            'state' => 'required|string|min:1|max:100',
            'theme'=> 'required|string|min:1|max:100',
            'forms' => 'required|string|min:1',
            'attachments' => 'required|string|min:1',
            'citizen_level' => 'required|string|min:1',
        ];
    }
}
