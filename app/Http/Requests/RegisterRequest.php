<?php

namespace App\Http\Requests;

use App\Http\Controllers\Api\ResponseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'phone' => 'nullable|digits_between:10,15',
            'address' => 'nullable|string|max:255',
            'role_id' => 'integer|exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.min' => 'Please enter a valid name',

            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already registered, please use a different email or log in instead',

            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 6 characters long',

            'password_confirmation.same' => 'Password and confirm password do not match',

            'phone.digits_between' => 'Please enter a valid phone number',

            'role_id.exists' => 'Invalid role selected'
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
