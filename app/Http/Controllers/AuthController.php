<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:25|unique:user,phone_number'
        ]);

        $user = User::create($request->only([
            'name', 'email', 'password', 'phone_number'
        ]));

        $token = bin2hex(random_bytes(40));
        
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

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

        $token = bin2hex(random_bytes(40));

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function googleLogin(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string'
        ]);

        try {
            $googleUser = Socialite::driver('google')->userFromToken($request->token);
            
            $user = User::updateOrCreate([
                'email' => $googleUser->email
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'email' => $googleUser->email
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Google login successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function facebookLogin(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string'
        ]);

        try {
            $facebookUser = Socialite::driver('facebook')->userFromToken($request->token);
            
            $user = User::updateOrCreate([
                'email' => $facebookUser->email
            ], [
                'name' => $facebookUser->name,
                'facebook_id' => $facebookUser->id,
                'avatar' => $facebookUser->avatar,
                'email' => $facebookUser->email
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Facebook login successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Facebook login failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}