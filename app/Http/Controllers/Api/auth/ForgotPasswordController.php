<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    // إرسال رابط إعادة تعيين كلمة المرور
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        // إرسال رابط إعادة تعيين كلمة المرور
        $status = Password::sendResetLink($request->only('email'));

        // التحقق من النتيجة وإرجاع الاستجابة المناسبة
        return $status === Password::RESET_LINK_SENT
            ? $this->successResponse(null, 'Password reset link sent to your email.')
            : $this->errorResponse('An error occurred while sending the reset link.', 400);
    }

    // إعادة تعيين كلمة المرور
    public function reset(ResetPasswordRequest $request)
    {
        // محاولة إعادة تعيين كلمة المرور
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // التحقق من النتيجة وإرجاع الاستجابة المناسبة
        return $status === Password::PASSWORD_RESET
            ? $this->successResponse(null, 'Password has been successfully reset.')
            : $this->errorResponse('An error occurred while resetting the password.', 400);
    }
}

