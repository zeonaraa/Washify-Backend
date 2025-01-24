<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login Function
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan validasi.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('username', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal.',
                'error' => 'Username atau password salah.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function me()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak terautentikasi.',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengguna berhasil diambil.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengguna.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'nama' => 'required|string|max:255',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan validasi.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = auth()->user();
            $user->username = $request->input('username');
            $user->nama = $request->input('nama');
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
            return response()->json([
                'success' => true,
                'message' => 'Token berhasil diperbarui.',
                'token' => $newToken,
                'user' => auth()->user(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui token.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
