<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address',
            'email.email' => 'Email must be a valid email address',
            'email.exists' => 'Email does not exist',
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
