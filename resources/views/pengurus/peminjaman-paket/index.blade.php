<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Peminjaman Buku Paket
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 sm:gap-0 mb-3 sm:mb-4">
                    <h3 class="text-sm sm:text-lg font-semibold">Daftar Peminjaman Buku Paket</h3>
                    <a href="{{ route('pengurus.peminjaman-paket.create') }}"
                       class="bg-blue-600 text-white px-3 sm:px-4 py-2 rounded text-xs sm:text-sm text-center hover:bg-blue-700">
                        + Pinjam Buku Paket
                    </a>
                </div>

                <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-4 sm:flex-wrap">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode / nama / kelas / judul buku..."
                           class="border rounded px-3 py-2 w-full sm:w-72 text-xs sm:text-sm">
                    <select name="status" class="border rounded px-3 py-2 text-xs sm:text-sm w-full sm:w-auto">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                        <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 sm:flex-none bg-gray-600 text-white px-4 py-2 rounded text-xs sm:text-sm">Cari</button>
                        <a href="{{ route('pengurus.peminjaman-paket.index') }}"
                           class="flex-1 sm:flex-none bg-gray-200 text-gray-700 px-4 py-2 rounded text-xs sm:text-sm text-center">Reset</a>
                    </div>
                </form>

                <div class="overflow-x-auto -mx-3 sm:mx-0">
                <table class="w-full text-xs sm:text-sm text-left border">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Kode</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold">Buku</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Jumlah</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Peminjam</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Kelas / Mapel</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Guru</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Tgl Pinjam</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Tgl Kembali</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Status</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paket as $item)
                        <tr>
                            <td class="px-2 sm:px-4 py-2 border font-mono text-[10px] sm:text-xs whitespace-nowrap">{{ $item->kode_peminjaman }}</td>
                            <td class="px-2 sm:px-4 py-2 border">{{ $item->buku->judul }}</td>
                            <td class="px-2 sm:px-4 py-2 border text-center">{{ $item->jumlah }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">{{ $item->nama_peminjam }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">{{ $item->kelas }} / {{ $item->mata_pelajaran }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">{{ $item->guru }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">{{ $item->tgl_pinjam->format('d/m/y') }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">{{ $item->tgl_kembali->format('d/m/y') }}</td>
                            <td class="px-2 sm:px-4 py-2 border">
                                @if($item->status == 'dipinjam')
                                    <span class="bg-indigo-100 text-indigo-700 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold whitespace-nowrap">Dipinjam</span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold whitespace-nowrap">
                                        Selesai ({{ $item->jumlah_kembali }}/{{ $item->jumlah }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 sm:px-4 py-2 border">
                                @if($item->status == 'dipinjam')
                                    <button onclick="document.getElementById('modal-{{ $item->id }}').classList.remove('hidden')"
                                            class="bg-green-500 text-white px-2 py-1 rounded text-[10px] sm:text-xs hover:bg-green-600 whitespace-nowrap">
                                        Konfirmasi
                                    </button>

                                    <div id="modal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                        <div class="absolute inset-0 bg-black/40"
                                             onclick="document.getElementById('modal-{{ $item->id }}').classList.add('hidden')"></div>
                                        <div class="relative z-10 bg-white rounded-xl shadow-2xl p-4 sm:p-6 max-w-sm w-full">
                                            <h3 class="font-bold text-gray-800 mb-3">Konfirmasi Pengembalian</h3>
                                            <form action="{{ route('pengurus.peminjaman-paket.kembalikan', $item) }}" method="POST" class="space-y-3">
                                                @csrf @method('PATCH')
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Jumlah buku yang kembali (dari {{ $item->jumlah }})
                                                    </label>
                                                    <input type="number" name="jumlah_kembali" min="0" max="{{ $item->jumlah }}"
                                                           value="{{ $item->jumlah }}" class="w-full border rounded px-3 py-2 text-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Keterangan <span class="text-gray-400">(opsional, misal jika ada yang rusak/hilang)</span>
                                                    </label>
                                                    <textarea name="keterangan" rows="2" class="w-full border rounded px-3 py-2 text-sm"></textarea>
                                                </div>
                                                <button type="submit"
                                                        class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            <button onclick="document.getElementById('modal-{{ $item->id }}').classList.add('hidden')"
                                                    class="mt-2 text-xs text-gray-400 hover:text-gray-600 w-full text-center">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center px-4 py-4 text-gray-500 text-xs sm:text-sm">
                                Belum ada data peminjaman buku paket
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="mt-4">{{ $paket->links() }}</div>

                <a href="{{ route('pengurus.peminjaman.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-xs sm:text-sm font-medium inline-flex items-center gap-1 mt-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Peminjaman Utama
                </a>
            </div>
        </div>
    </div>
</x-app-layout>