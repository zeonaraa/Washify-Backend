<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => auth()->user(),
        ]);
    }

    // Logout Function
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    // Get Authenticated User Info
    public function me()
    {
        return response()->json(auth()->user());
    }
}
