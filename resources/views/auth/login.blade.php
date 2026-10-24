<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Username --}}
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
                    </svg>
                </div>
                <input id="username"
                       type="text"
                       name="username"
                       value="{{ old('username') }}"
                       required
                       autofocus
                       placeholder="Masukkan NISN atau Nama Lengkap"
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-gray-50 focus:bg-white">
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password"
       type="password"
       name="password"
       required
       placeholder="Masukkan password"
       class="w-full pl-10 pr-12 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-gray-50 focus:bg-white">
<button type="button"
        onclick="togglePassword('password', this)"
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 014.02-5.197M9.879 9.879A3 3 0 0012 15a3 3 0 002.121-5.121M3 3l18 18"/>
    </svg>
</button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Info login --}}
        <div class="bg-sky-50 border border-sky-100 rounded-xl px-4 py-3">
            <p class="text-xs font-semibold text-sky-700 mb-1.5">Panduan Login:</p>
            <div class="space-y-1">
                <p class="text-xs text-sky-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-sky-400 rounded-full inline-block"></span>
                    <strong>Guru</strong> → Username: Nama Lengkap
                </p>
                <p class="text-xs text-sky-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-sky-400 rounded-full inline-block"></span>
                    <strong>Siswa</strong> → Username: NISN
                </p>
            </div>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <input id="remember_me"
                   type="checkbox"
                   name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-sky-600 focus:ring-sky-400">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat Saya</label>
        </div>

        {{-- Tombol masuk --}}
        <button type="submit"
                class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm shadow-sky-200">
            Masuk ke Sistem
        </button>

        {{-- Link tambahan --}}
        <div class="flex flex-col items-center gap-2 pt-1">
            <a href="{{ route('register.pilih') }}"
               class="text-xs text-sky-600 hover:text-sky-800 hover:underline transition">
                Belum punya akun? <span class="font-semibold">Daftar di sini</span>
            </a>
            <a href="{{ route('password.request') }}"
               class="text-xs text-gray-400 hover:text-gray-600 hover:underline transition">
                Lupa Password?
            </a>
        </div>
    </form>

    <script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
    const isNowHidden = field.type === 'password';
    btn.querySelector('svg').innerHTML = isNowHidden
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 014.02-5.197M9.879 9.879A3 3 0 0012 15a3 3 0 002.121-5.121M3 3l18 18"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>
</x-guest-layout>