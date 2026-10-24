<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Manajemen Kategori
            </h2>
            <a href="{{ route('pengurus.kategori.create') }}"
               class="shrink-0 whitespace-nowrap bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                + <span class="hidden sm:inline">Tambah Kategori</span><span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ===== MOBILE: daftar kartu ===== --}}
            <div class="space-y-3 md:hidden">
                @forelse($kategori as $item)
                    {{-- PERUBAHAN 3: items-start -> items-center --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center flex-wrap gap-2">
                                <h3 class="font-semibold text-gray-800">{{ $item->nama }}</h3>
                                <span class="whitespace-nowrap bg-sky-50 text-sky-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $item->buku_count }} buku
                                </span>
                            </div>
                            @if($item->deskripsi)
                                <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $item->deskripsi }}</p>
                            @endif
                        </div>

                        <form action="{{ route('pengurus.kategori.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori ini?')" class="shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="bg-red-50 text-red-600 hover:bg-red-100 text-sm font-medium px-3 py-2 rounded-lg transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 px-6 py-12 text-center text-gray-400 text-sm">
                        Belum ada kategori. Tambahkan kategori baru!
                    </div>
                @endforelse
            </div>

            {{-- PERUBAHAN 1: tombol kembali (HP) --}}
            <a href="{{ route('pengurus.buku.index') }}"
               class="md:hidden w-full flex items-center justify-center gap-2 border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 active:bg-gray-100 px-4 py-3 rounded-xl text-sm font-medium shadow-sm transition">
                ← Kembali ke Manajemen Buku
            </a>

            {{-- ===== DESKTOP/TABLET: tabel ===== --}}
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Jumlah Buku</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($kategori as $i => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="whitespace-nowrap bg-sky-50 text-sky-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $item->buku_count }} buku
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('pengurus.kategori.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                Belum ada kategori. Tambahkan kategori baru!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PERUBAHAN 2: tombol kembali (desktop) --}}
            <div class="hidden md:block">
                <a href="{{ route('pengurus.buku.index') }}"
                   class="inline-flex items-center gap-2 border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition">
                    ← Kembali ke Manajemen Buku
                </a>
            </div>

        </div>
    </div>
</x-app-layout>