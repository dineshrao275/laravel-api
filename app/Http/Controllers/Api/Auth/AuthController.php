<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends BaseController
{
    protected $data = [];

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        if ($user) {
            auth()->login($user);
            $this->data['token'] = $user->createToken('auth_token')->plainTextToken;
            $this->data['user'] = $user;
            return $this->res('User created successfully', true, $this->data, 201);
        }
        return $this->res('Failed to create user', false, [], 400);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (!auth()->attempt($data)) {
            return $this->res('Invalid credentials', false, [], 401);
        }
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $this->data['token'] = $token;
        $this->data['user'] = $user;
        if ($user) {
            return $this->res('User login successfully', true, $this->data, 201);
        }
        return $this->res('Failed to login user', false, [], 400);
    }

    public function logout()
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return $this->res('You are not logged in, please login', false, [], 400);
        }
        $user->tokens()->delete();
        return $this->res('User logout successfully', true, [], 201);
    }
}
