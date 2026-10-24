<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Perpustakaan SMKN 1 Bojongpicung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-900 min-h-screen flex items-center justify-center px-4 py-8 sm:py-4">

    <div class="w-full max-w-md">
        {{-- Header --}}
                <div class="text-center mb-5 sm:mb-8">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center text-xl sm:text-3xl mx-auto mb-3 sm:mb-4 shadow-lg">
                🔐
            </div>
            <h1 class="text-white text-lg sm:text-2xl font-bold">Portal Admin</h1>
            <p class="text-sky-300 text-xs sm:text-sm mt-1">Perpustakaan SMK Negeri 1 Bojongpicung</p>
        </div>
        {{-- Card Form --}}
               <div class="bg-white rounded-2xl shadow-2xl p-5 sm:p-8">
            <h2 class="text-gray-800 font-bold text-base sm:text-lg mb-1">Masuk sebagai Admin</h2>
            <p class="text-gray-400 text-xs mb-4 sm:mb-6">Khusus Pengurus & Kepala Perpustakaan</p>
            {{-- Session Status --}}
            @if(session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf

                {{-- Username --}}
                <div class="mb-3 sm:mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           placeholder="Masukkan nama lengkap" required autofocus>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5 sm:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="adminPassword"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 pr-10"
                               placeholder="Masukkan password" required>
                        <button type="button" onclick="togglePassword('adminPassword', this)"
        class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700 flex items-center">
    {{-- Ikon mata terbuka (ditampilkan saat password TERLIHAT) --}}
    <svg id="eyeOpen-adminPassword" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    {{-- Ikon mata tercoret (default, ditampilkan saat password TERSEMBUNYI) --}}
    <svg id="eyeClosed-adminPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
    </svg>
</button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-sky-700 hover:bg-sky-800 text-white font-semibold py-2 rounded-lg transition text-sm">
                    Masuk
                </button>
            </form>

            <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-100 text-center">
                <a href="{{ route('welcome') }}" class="text-sky-600 text-xs hover:underline">
                    ← Kembali ke Halaman Utama
                </a>
            </div>
        </div>

                <div class="mt-6">
            <x-copyright :dark="true" />
        </div>
    </div>



    <script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const eyeOpen = document.getElementById('eyeOpen-' + fieldId);
    const eyeClosed = document.getElementById('eyeClosed-' + fieldId);

    if (field.type === 'password') {
        // Password akan jadi TERLIHAT -> tampilkan mata TERBUKA
        field.type = 'text';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    } else {
        // Password akan jadi TERSEMBUNYI -> tampilkan mata TERCORET
        field.type = 'password';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    }
}
</script>
</body>
</html>