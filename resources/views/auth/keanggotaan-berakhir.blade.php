<x-guest-layout>
    <div class="text-center">
        <div class="text-6xl mb-4">🚫</div>
        <h2 class="text-xl font-bold text-red-600 mb-2">
            Keanggotaan Perpustakaan Berakhir
        </h2>
        <p class="text-gray-600 text-sm mb-6">
            Masa keanggotaan perpustakaan kamu sudah berakhir karena 
            kamu telah menyelesaikan studi di sekolah ini.
            Silahkan hubungi pengurus perpustakaan untuk informasi lebih lanjut.
        </p>
        <a href="{{ route('login') }}" 
           class="text-blue-600 hover:underline text-sm">
            ← Kembali ke halaman login
        </a>
    </div>
</x-guest-layout>