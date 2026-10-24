@props(['dark' => false])

<p class="text-center text-xs {{ $dark ? 'text-sky-300' : 'text-gray-400' }}">
    © {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung
</p>
<p class="text-center text-xs mt-1 {{ $dark ? 'text-sky-400' : 'text-gray-500' }}">
    Dibuat oleh <span class="font-semibold {{ $dark ? 'text-yellow-300' : 'text-sky-600' }}">@yeshiana</span>
</p>