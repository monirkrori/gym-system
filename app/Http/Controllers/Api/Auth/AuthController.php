<?php

namespace App\Http\Controllers\Api\Auth;

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

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The validated registration request containing name, email, and password.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attempt to send an email verification notification
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send verification email. Please try again.', 500);
        }

        // Fire an event for user registration (e.g., for logging or other actions)
        event(new UserRegistered($user));

        return $this->successResponse($user, 'Registered successfully. Please verify your email.');
    }

    /**
     * Log in a user.
     *
     * @param LoginRequest $request The validated login request containing email and password.
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Validate the provided credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            return $this->errorResponse('Please verify your email first.', 403);
        }

        // Generate an access token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request The current HTTP request.
     */
    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();

        // Check if the user's email is already verified
        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse('Your email is already verified.', 400);
        }

        // Attempt to resend the email verification notification
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send verification email. Please try again.', 500);
        }

        return $this->successResponse(null, 'Verification email resent successfully.');
    }

    /**
     * Log out the currently authenticated user.
     *
     * @param Request $request The current HTTP request.
     */
    public function logout(Request $request)
    {
        // Revoke the user's current access token
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
