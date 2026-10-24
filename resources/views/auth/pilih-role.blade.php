<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Akun</h2>
        <p class="text-gray-500 text-sm mt-1">Pilih daftar sebagai apa</p>
    </div>

    <div class="flex flex-col gap-4">
        <a href="{{ route('register.siswa') }}"
           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition">
            🎓 Daftar sebagai Siswa
        </a>
        <a href="{{ route('register.guru') }}"
           class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-lg transition">
            👨‍🏫 Daftar sebagai Guru
        </a>
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
            Sudah punya akun? Masuk di sini
        </a>
    </div>
</x-guest-layout>