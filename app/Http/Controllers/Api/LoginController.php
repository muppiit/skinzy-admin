<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    // Method untuk login API dengan JWT
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => 'required', // Login bisa berupa email atau username
            'password' => 'required',
        ]);

        // Persiapkan kredensial berdasarkan input login
        $credentials = ['password' => $request->password];

        // Tentukan apakah login menggunakan email atau username
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->login;
        } else {
            $credentials['username'] = $request->login;
        }

        // Coba login menggunakan JWT
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401); // Kredensial salah
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500); // Gagal membuat token
        }

        // Jika login berhasil, kembalikan token
        return $this->respondWithToken($token);
    }

    // Method untuk logout API
    public function logout()
    {
        // Logout pengguna
        try {
            JWTAuth::invalidate(JWTAuth::getToken()); // Invalidate token yang sedang aktif
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out'], 500); // Gagal logout
        }
    }

    // Method untuk refresh token
    public function refresh()
    {
        try {
            $refreshedToken = JWTAuth::refresh(); // Refresh token yang telah kadaluarsa
            return $this->respondWithToken($refreshedToken);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to refresh token'], 500); // Gagal refresh token
        }
    }

    // Helper untuk membungkus response dengan token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 // Waktu kedaluwarsa dalam detik
        ]);
    }
}
