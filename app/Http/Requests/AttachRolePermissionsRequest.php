<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttachRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'permission_ids'   => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'permission_ids.required'    => 'Please select at least one permission to attach.',
            'permission_ids.*.exists'    => 'One or more selected permissions do not exist.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $responseController = app(\App\Http\Controllers\Api\BaseController::class);

        throw new HttpResponseException(
            $responseController->res($validator->errors()->first(), false, [], 422)
        );
    }
}
