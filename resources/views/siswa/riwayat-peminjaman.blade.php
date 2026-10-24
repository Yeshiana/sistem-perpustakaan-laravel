<x-app-layout>
        <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

                        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Riwayat Peminjaman Buku</h3>
                    <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5">Semua transaksi peminjaman kamu</p>

                    <form method="GET" class="mt-3 flex gap-2">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari judul buku atau kode..."
                                   class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                        </div>
                        <button type="submit"
                                class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition shrink-0">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('siswa.riwayat') }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                                
                                    <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-sky-50 border-b border-sky-100">
                    <tr>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase whitespace-nowrap border-r border-sky-100">No</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase border-r border-sky-100">Judul Buku</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase border-r border-sky-100">Tanggal Booking</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase border-r border-sky-100">Tanggal Ambil</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase border-r border-sky-100">Tanggal Kembali</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase whitespace-nowrap border-r border-sky-100">Status</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs font-semibold text-sky-700 uppercase whitespace-nowrap">Foto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                                                       @forelse($peminjaman as $item)
                            <tr class="hover:bg-gray-50 transition">
                                
                        <td class="px-2 sm:px-4 py-2 sm:py-3 text-gray-400 text-[10px] sm:text-xs border-r border-gray-100">{{ $loop->iteration }}</td>

                                {{-- Judul Buku --}}
                                <td class="px-2 sm:px-4 py-2 sm:py-3 font-medium text-gray-800 max-w-[100px] sm:max-w-[180px] border-r border-gray-100">
                                    <p class="line-clamp-2 text-[10px] sm:text-xs leading-snug">{{ $item->buku->judul }}</p>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs text-gray-500 whitespace-nowrap border-r border-gray-100">
                                    {{ $item->tgl_booking ? $item->tgl_booking->format('d/m/y') : '-' }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs text-gray-500 whitespace-nowrap border-r border-gray-100">
                                    @if($item->tgl_ambil_fix)
                                        {{ $item->tgl_ambil_fix->format('d/m/y') }}
                                    @elseif($item->tgl_ambil)
                                        <span class="text-gray-400 italic">{{ $item->tgl_ambil->format('d/m/y') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-[10px] sm:text-xs text-gray-500 whitespace-nowrap border-r border-gray-100">
                                    {{ $item->tgl_kembali ? $item->tgl_kembali->format('d/m/y') : '-' }}
                                </td>
                                 {{-- Status --}}
                                <td class="px-2 sm:px-4 py-2 sm:py-3 border-r border-gray-100">
                                    @php
                                        $statusLabel = [
                                            'menunggu'            => 'Menunggu',
                                            'disetujui'           => 'Disetujui',
                                            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                            'dipinjam'            => 'Dipinjam',
                                            'selesai'             => 'Selesai',
                                            'ditolak'             => 'Ditolak',
                                            'dibatalkan'          => 'Dibatalkan',
                                        ][$item->status] ?? ucfirst(str_replace('_', ' ', $item->status));

                                        $statusColor = [
                                            'menunggu'            => 'bg-yellow-100 text-yellow-700',
                                            'disetujui'           => 'bg-blue-100 text-blue-700',
                                            'menunggu_konfirmasi' => 'bg-orange-100 text-orange-700',
                                            'dipinjam'            => 'bg-indigo-100 text-indigo-700',
                                            'selesai'             => 'bg-green-100 text-green-700',
                                            'ditolak'             => 'bg-red-100 text-red-700',
                                            'dibatalkan'          => 'bg-gray-100 text-gray-700',
                                        ][$item->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                        <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-xs font-semibold whitespace-nowrap {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Dokumentasi --}}
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    @if($item->bukti_pengambilan)
                                        <button type="button"
                                            onclick="openFotoModal('{{ asset('storage/'.$item->bukti_pengambilan) }}')">
                                            <img src="{{ asset('storage/'.$item->bukti_pengambilan) }}"
                                                 class="w-9 h-9 sm:w-14 sm:h-14 object-cover rounded-lg border hover:opacity-80 transition">
                                        </button>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center px-4 py-12 text-gray-400 text-sm">
                                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                                    </svg>
                                    Belum ada riwayat peminjaman
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>    
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $peminjaman->links() }}
                </div>
            </div>
                </div>
    </div>

    {{-- MODAL FOTO DOKUMENTASI --}}
    <div id="fotoModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4"
         onclick="if(event.target === this) closeFotoModal()">

        <div class="relative max-w-2xl w-full">
            <button type="button" onclick="closeFotoModal()"
                class="absolute -top-10 right-0 sm:-top-3 sm:-right-3 bg-white text-gray-700 rounded-full w-9 h-9 flex items-center justify-center shadow-lg hover:bg-gray-100 transition text-xl">
                &times;
            </button>
            <img id="fotoModalImg" src=""
                 class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl bg-white">
        </div>

    </div>

    <script>
        function openFotoModal(src) {
            document.getElementById('fotoModalImg').src = src;
            const modal = document.getElementById('fotoModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeFotoModal() {
            const modal = document.getElementById('fotoModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>