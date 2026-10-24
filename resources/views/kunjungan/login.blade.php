<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kunjungan Perpustakaan</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-900 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg">
                📚
            </div>
            <h1 class="text-white text-2xl font-bold">Kunjungan Perpustakaan</h1>
            <p class="text-sky-300 text-sm mt-1">SMK Negeri 1 Bojongpicung</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-gray-800 font-bold text-lg mb-1">Selamat Datang!</h2>
            <p class="text-gray-400 text-xs mb-6">Masuk untuk mencatat kunjungan kamu</p>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('kunjungan.login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        NISN (Siswa) / Nama Lengkap (Guru)
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                           placeholder="Masukkan NISN atau Nama" required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 pr-10"
                               placeholder="Masukkan password" required>
                        <button type="button" onclick="togglePassword('password', this)"
                                class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700">
                            🙈
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-sky-700 hover:bg-sky-800 text-white font-semibold py-2 rounded-lg transition text-sm">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-sky-400 text-xs mt-6">
            © {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung
        </p>
    </div>

    <script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            btn.textContent = '🐵';
        } else {
            field.type = 'password';
            btn.textContent = '🙈';
        }
    }
    </script>
</body>
</html>