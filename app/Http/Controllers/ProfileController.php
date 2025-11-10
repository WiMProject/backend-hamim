<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Profile Controller
 * 
 * Handles user profile management operations
 */
class ProfileController extends Controller
{
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

    /**
     * Get user profile information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'Profile retrieved successfully',
            'user' => $user
        ]);
    }

    /**
     * Update user profile information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->validate($request, [
            'name' => 'sometimes|string|max:100',
            'phone_number' => 'sometimes|string|max:25|unique:user,phone_number,' . $user->id,
            'address' => 'sometimes|string|max:255',
            'profile_picture' => 'sometimes|string|max:500'
        ]);

        $user->update($request->only([
            'name', 'phone_number', 'address', 'profile_picture'
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }
}