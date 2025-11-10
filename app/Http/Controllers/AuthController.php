<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Authentication Controller
 * 
 * Handles user registration, login, password management, and Firebase authentication
 */
class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6' . ($request->has('password_confirmation') ? '|confirmed' : ''),
            'phone_number' => 'required|string|max:25|unique:user,phone_number'
        ]);

        $user = User::create($request->only([
            'name', 'email', 'password', 'phone_number'
        ]));

        $token = $user->createToken('auth-token')->plainTextToken;
        
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Authenticate user with email and password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Logout user (placeholder - token deletion should be implemented)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Generate password reset token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:user,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $resetToken = Str::random(60);
        
        $user->update([
            'remember_token' => $resetToken
        ]);

        return response()->json([
            'message' => 'Password reset token generated',
            'reset_token' => $resetToken,
            'email' => $user->email
        ]);
    }

    /**
     * Reset password using token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:user,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid reset token'
            ], 400);
        }

        $user->update([
            'password' => $request->password,
            'remember_token' => null
        ]);

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Change user password (requires current password)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = $this->getAuthenticatedUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => $request->new_password
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Authenticate user with Firebase token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function firebaseLogin(Request $request)
    {
        $this->validate($request, [
            'idToken' => 'required|string'
        ]);

        try {
            $firebaseService = new FirebaseService();
            $firebaseUser = $firebaseService->verifyIdToken($request->idToken);
            
            $user = User::updateOrCreate([
                'email' => $firebaseUser['email']
            ], [
                'name' => $firebaseUser['name'],
                'firebase_uid' => $firebaseUser['uid'],
                'avatar' => $firebaseUser['picture'],
                'email' => $firebaseUser['email']
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Firebase login successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Firebase login failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get authenticated user from Bearer token
     *
     * @param Request $request
     * @return User|null
     */
    private function getAuthenticatedUser(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            return null;
        }

        return $accessToken->tokenable;
    }
}