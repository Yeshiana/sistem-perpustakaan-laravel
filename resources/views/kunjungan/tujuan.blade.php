<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Tujuan Kunjungan</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-900 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg">
                📚
            </div>
            <h1 class="text-white text-2xl font-bold">Halo, {{ auth()->user()->name }}!</h1>
            <p class="text-sky-300 text-sm mt-1">Pilih tujuan kunjunganmu hari ini</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <form method="POST" action="{{ route('kunjungan.simpan') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <button type="submit" name="tujuan" value="membaca"
                            class="flex flex-col items-center justify-center p-6 border-2 border-sky-100 rounded-xl hover:border-sky-500 hover:bg-sky-50 transition text-center">
                        <span class="text-4xl mb-2">📖</span>
                        <span class="font-semibold text-gray-800 text-sm">Membaca</span>
                    </button>
                    <button type="submit" name="tujuan" value="mengerjakan_tugas"
                            class="flex flex-col items-center justify-center p-6 border-2 border-sky-100 rounded-xl hover:border-sky-500 hover:bg-sky-50 transition text-center">
                        <span class="text-4xl mb-2">✏️</span>
                        <span class="font-semibold text-gray-800 text-sm">Mengerjakan Tugas</span>
                    </button>
                    <button type="submit" name="tujuan" value="peminjaman"
                            class="flex flex-col items-center justify-center p-6 border-2 border-sky-100 rounded-xl hover:border-sky-500 hover:bg-sky-50 transition text-center">
                        <span class="text-4xl mb-2">📋</span>
                        <span class="font-semibold text-gray-800 text-sm">Peminjaman</span>
                    </button>
                    <button type="submit" name="tujuan" value="pengembalian"
                            class="flex flex-col items-center justify-center p-6 border-2 border-sky-100 rounded-xl hover:border-sky-500 hover:bg-sky-50 transition text-center">
                        <span class="text-4xl mb-2">📦</span>
                        <span class="font-semibold text-gray-800 text-sm">Pengembalian</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-sky-400 text-xs mt-6">
            © {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung
        </p>
    </div>

</body>
</html>