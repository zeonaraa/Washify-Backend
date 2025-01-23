<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Login Function
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'error' => 'The username or password is incorrect',
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    // Logout Function
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    public function me()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

public function updateMe(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
        'nama' => 'required|string|max:255',
    ]);

    try {
        $user = auth()->user();
        $user->username = $request->input('username');
        $user->nama = $request->input('nama');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update profile',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function refresh()
    {
        $newToken = auth()->refresh();
        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $newToken,
            'user' => auth()->user(),
        ]);
    }
}
