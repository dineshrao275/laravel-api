<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SyncUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'role_ids'   => 'required|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_ids.required'    => 'Please select at least one role.',
            'role_ids.*.exists'    => 'One or more selected roles do not exist.',
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
