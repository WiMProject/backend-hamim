<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        $token = $user->createToken('auth-token')->plainTextToken;
        
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

        $token = $user->createToken('auth-token')->plainTextToken;

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
}