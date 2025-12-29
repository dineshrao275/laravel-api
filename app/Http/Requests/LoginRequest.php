<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'password' => 'required|min:6'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 6 characters long',
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
