<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Coba login dengan NISN (siswa)
        $loginBerhasil = Auth::attempt([
            'nisn'     => $this->username,
            'password' => $this->password,
        ], $this->boolean('remember'));

        // Kalau gagal, coba login dengan email (guru/pengurus/kepala)
        if (!$loginBerhasil) {
            $loginBerhasil = Auth::attempt([
                'name'    => $this->username,
                'password' => $this->password,
            ], $this->boolean('remember'));
        }

        if (!$loginBerhasil) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        // Cek apakah akun siswa masih aktif
        $user = Auth::user();
        if ($user->role === 'siswa' && !$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Keanggotaan perpustakaan kamu sudah berakhir.',
            ]);
        }

        // Cek tanggal kadaluarsa siswa
        if ($user->role === 'siswa' && $user->tanggal_aktif_hingga) {
            if (now()->gt($user->tanggal_aktif_hingga)) {
                // Update is_active jadi false
                $user->update(['is_active' => false]);
                Auth::logout();
                return;
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}