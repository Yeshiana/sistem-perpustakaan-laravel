<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kunjungan Tercatat</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-900 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <div class="text-5xl mb-4">🎉</div>
            <h1 class="text-gray-800 font-bold text-lg">Selamat Datang, {{ $nama }}!</h1>
            <p class="text-gray-400 text-sm mt-2">
                Kunjunganmu sudah tercatat. Selamat menikmati layanan perpustakaan digital kami.
            </p>
            <a href="{{ route('kunjungan.index') }}"
               class="inline-block mt-6 bg-sky-700 hover:bg-sky-800 text-white rounded-lg px-6 py-2.5
                      text-sm font-semibold transition">
                Kembali ke Form
            </a>
        </div>

        <p class="text-center text-sky-400 text-xs mt-6">
            © {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung
        </p>
    </div>

</body>
</html>