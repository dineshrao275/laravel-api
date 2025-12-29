<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\PasswordResetNotification;

class PasswordController extends BaseController
{
    public function forgotPassword(ForgotPasswordRequest $request) {
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('password_reset_token')->plainTextToken;
        $user->notify(new PasswordResetNotification($token));
        return $this->res('Password reset link sent successfully to your email', true, [], 201);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = $user->tokens()->where('tokenable_id', $user->id)->where('name', 'password_reset_token')->first();
        if (!$token) {
            return $this->res('This token is invalid, please try again', true, [], 201);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $user->tokens()->where('tokenable_id', $user->id)->where('name', 'password_reset_token')->delete();

        return $this->res('Password reset successfully', true, [], 201);
    }
}
