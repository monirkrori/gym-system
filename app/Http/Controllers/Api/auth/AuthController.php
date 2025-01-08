<?php

namespace App\Http\Controllers\Api\auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    // Register a new user
    public function register(RegisterRequest $request)
    {
        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //send email
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send verification email. Please try again.', 500);
        }
        
        event(new UserRegistered($user));
        return $this->successResponse($user, 'Register successfully, please verify your email.');
    }

    // Login a user
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->errorResponse('Please verify your email first.', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse('Your email is already verified.', 400);
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send verification email. Please try again.', 500);
        }

        return $this->successResponse(null, 'Verification email resent successfully.');
    }

    // Logout a user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
