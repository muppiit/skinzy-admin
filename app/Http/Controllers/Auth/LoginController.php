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

        // Persiapkan kredensial berdasarkan inputan login
        $credentials = ['password' => $request->password];

        // Cek apakah input login berupa email atau username
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            // Input login adalah email
            $credentials['email'] = $request->login;
        } else {
            // Input login adalah username
            $credentials['username'] = $request->login;
        }

        // Coba login menggunakan kredensial yang telah disiapkan
        if (Auth::attempt($credentials)) {
            // Jika login berhasil, arahkan ke halaman dashboard sesuai role pengguna
            return $this->handleRedirect(Auth::user());
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

   
    protected function handleRedirect($user)
    {
        if ($user->level == 'admin') {
            // Jika user adalah admin, arahkan ke dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa, arahkan ke halaman home atau dashboard user
        return redirect()->route('user.dashboard'); // Sesuaikan rute ini
    }

    
    public function logout()
    {
        // Logout pengguna
        Auth::logout();

        // Redirect ke halaman login
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
