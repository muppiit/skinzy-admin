<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
         // Validasi input
         $request->validate([
            'login' => 'required', // Mengganti email dengan login untuk menerima username atau email
            'password' => 'required',
        ]);

        // Cek apakah login menggunakan email atau username
        $credentials = [
            'password' => $request->password,
        ];

        // Jika input login adalah email, tambahkan key email ke credentials
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->login;
        } else {
            // Jika bukan email, asumsikan itu adalah username
            $credentials['username'] = $request->login;
        }

        // Coba login
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home'); // Ganti dengan route tujuan setelah login
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }
}
