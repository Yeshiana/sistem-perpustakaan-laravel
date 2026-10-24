<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // Tampilkan halaman login admin
    public function showLoginForm()
    {
        // Kalau sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.admin-login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Hanya pengurus & kepala yang boleh masuk lewat sini
            if (!in_array($user->role, ['pengurus', 'kepala'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses admin.',
                ]);
            }

            $request->session()->regenerate();
            return $this->redirectByRole($user->role);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Redirect berdasarkan role
    private function redirectByRole($role)
    {
        return match($role) {
            'pengurus' => redirect()->route('pengurus.dashboard'),
            'kepala'   => redirect()->route('kepala.dashboard'),
            default    => redirect()->route('admin.login'),
        };
    }
}