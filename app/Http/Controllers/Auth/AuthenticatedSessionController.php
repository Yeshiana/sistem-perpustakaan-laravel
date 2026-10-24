<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // Cek apakah siswa sudah expired (logout dilakukan di LoginRequest)
    if (!auth()->check()) {
        return redirect()->route('keanggotaan.berakhir');
    }

    $role = $user->role;

    // Tambahkan di sini ↓
    if (!in_array($role, ['siswa', 'guru'])) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back()->withErrors(['username' => 'Gunakan halaman login admin untuk masuk.']);
    }

    return match($role) {
        'siswa', 'guru' => redirect()->route('siswa.dashboard'),
        'pengurus'      => redirect()->route('pengurus.dashboard'),
        'kepala'        => redirect()->route('kepala.dashboard'),
        default         => redirect('/'),
    };
}

    /**
     * Destroy an authenticated session.
     */

public function destroy(Request $request): RedirectResponse
{
    $role = auth()->user()->role;
    
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if (in_array($role, ['pengurus', 'kepala'])) {
        return redirect()->route('admin.login');
    }

    return redirect()->route('login');
}
}
