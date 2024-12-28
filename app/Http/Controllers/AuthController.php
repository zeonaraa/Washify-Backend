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
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ], 401);
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
