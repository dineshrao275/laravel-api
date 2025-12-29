<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a name.',
            'description.required' => 'Please enter a short description.',
        ];
    }

        protected function failedValidation(Validator $validator)
    {
        $baseController = app(\App\Http\Controllers\Api\BaseController::class);

        throw new HttpResponseException(
            $baseController->res($validator->errors()->first(), false, [], 422)
        );
    }

}
