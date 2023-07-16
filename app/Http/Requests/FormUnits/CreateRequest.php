<?php

namespace App\Http\Requests\FormUnits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

use App\Http\Modules\Admin\Forms\Fields\Validators\Enums\PropertyTypes as FieldPropertyTypes;
use App\Http\Modules\Admin\Forms\Fields\Validators\Enums\ValidatorTypes as FieldValidatorTypes;
use App\Http\Modules\Admin\Forms\Fields\Validators\Validators as BusinessValidators;
use App\Http\Shared\Enums\Statuses;

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
            'elements' => 'required|array|min:1',
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

//     public function withValidator($validatorInstance) {
//         for ($index = 0; $index < count($this['fields']); $index++) {
//             // validate property type
//             $propertyType = $this['fields'][$index]['properties']['type'];
//             if (!FieldPropertyTypes::isValid($propertyType)) {
//                 $validatorInstance->errors()->add(
//                     "fields->index[$index]->properties->type",
//                     "Invalid value"
//                 );
//             }
//
//             // validate validator type
//             $validatorType = $this['fields'][$index]['validators']['type'];
//             if (!FieldValidatorTypes::isValid($validatorType)) {
//                 $validatorInstance->errors()->add(
//                     "fields->index[$index]->validators->type",
//                     "Invalid value"
//                 );
//             }
//
//             // validate all other validators listed in form.fields.*.validators->keys(!'type')
//             $incommingValidators = array_keys($this['fields'][$index]);
//             unset($incommingValidators['type']);
//
//             foreach ($incommingValidators as $validator) {
//                 if (!BusinessValidators::isValid($validator)) {
//                     $validatorInstance->errors()->add(
//                         "fields->index[$index]->validators->$validator",
//                         "Invalid validator"
//                     );
//                 }
//             }
//         }
//
//         if (!Statuses::isValid($this['status'])) {
//             $validatorInstance->errors()->add(
//                 "status",
//                 "Invalid status"
//             );
//         }
//     }
}
