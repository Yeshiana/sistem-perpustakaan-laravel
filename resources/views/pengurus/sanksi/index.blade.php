<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Manajemen Sanksi
        </h2>
    </x-slot>

    {{-- ====== CSS RESPONSIF TABEL (HP: tabel berubah menjadi kartu) ====== --}}
    <style>
        @media (max-width: 767px) {
            table.tabel-sanksi,
            table.tabel-sanksi tbody {
                display: block;
                border: 0;
            }

            table.tabel-sanksi thead {
                display: none;
            }

            /* Setiap baris menjadi satu kartu */
            table.tabel-sanksi tr {
                display: block;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                padding: 0.5rem 0.875rem;
                margin-bottom: 0.75rem;
            }

            /* Setiap sel menjadi baris "label : nilai" */
            table.tabel-sanksi td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                border: 0;
                padding: 0.375rem 0;
            }

            table.tabel-sanksi td[data-label]::before {
                content: attr(data-label);
                flex-shrink: 0;
                font-size: 0.75rem;
                font-weight: 600;
                color: #6b7280;
                padding-top: 0.125rem;
            }

            table.tabel-sanksi td .nilai {
                text-align: right;
                min-width: 0;
                overflow-wrap: anywhere;
            }

            table.tabel-sanksi td.sembunyi-mobile {
                display: none;
            }

            table.tabel-sanksi td.sel-kosong {
                justify-content: center;
            }

            /* Kolom aksi: dua tombol berdampingan, selebar kartu */
            table.tabel-sanksi td.aksi-cell {
                gap: 0.5rem;
                margin-top: 0.25rem;
                padding-top: 0.75rem;
                border-top: 1px solid #f3f4f6;
            }

            table.tabel-sanksi td.aksi-cell > form {
                flex: 1;
                margin: 0;
            }

            table.tabel-sanksi td.aksi-cell .btn-aksi {
                width: 100%;
                padding: 0.625rem 0.75rem;
                font-size: 0.875rem;
                text-align: center;
            }
        }
    </style>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6">

                {{-- FORM PENCARIAN & FILTER --}}
                <form method="GET" action="{{ route('pengurus.sanksi.index') }}"
                      class="flex flex-col gap-2 mb-4 sm:flex-row sm:flex-wrap sm:gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / judul buku..."
                           class="border rounded px-3 py-2 w-full sm:w-64 text-base sm:text-sm">

                    <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                        <select name="jenis" class="border rounded px-3 py-2 w-full sm:w-auto text-base sm:text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="keterlambatan" {{ request('jenis')=='keterlambatan'?'selected':'' }}>Keterlambatan</option>
                            <option value="buku_rusak" {{ request('jenis')=='buku_rusak'?'selected':'' }}>Buku Rusak</option>
                            <option value="tidak_diambil" {{ request('jenis')=='tidak_diambil'?'selected':'' }}>Tidak Diambil</option>
                        </select>
                        <select name="status" class="border rounded px-3 py-2 w-full sm:w-auto text-base sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu Bukti</option>
                            <option value="menunggu_verifikasi" {{ request('status')=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                            <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                            <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                        <button type="submit" class="bg-gray-600 text-white px-4 py-2.5 sm:py-2 rounded text-sm">Cari</button>
                        <a href="{{ route('pengurus.sanksi.index') }}"
                           class="block text-center bg-gray-200 text-gray-700 px-4 py-2.5 sm:py-2 rounded text-sm">Reset</a>
                    </div>
                </form>

                <div class="md:overflow-x-auto">
                <table class="tabel-sanksi w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Siswa/Guru</th>
                            <th class="px-4 py-2 border">Buku</th>
                            <th class="px-4 py-2 border">Jenis Sanksi</th>
                            <th class="px-4 py-2 border">Keterangan</th>
                            <th class="px-4 py-2 border">Bukti</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanksi as $item)
                        <tr>
                            <td class="sembunyi-mobile px-4 py-2 border">{{ $loop->iteration }}</td>

                            <td class="px-4 py-2 border" data-label="Siswa/Guru">
                                <div class="nilai">
                                    {{ $item->user->name }}
                                    <span class="text-xs text-gray-500">({{ ucfirst($item->user->role) }})</span>
                                </div>
                            </td>

                            <td class="px-4 py-2 border" data-label="Buku">
                                <div class="nilai">{{ $item->peminjaman->buku->judul }}</div>
                            </td>

                            <td class="px-4 py-2 border" data-label="Jenis Sanksi">
                                <div class="nilai">
                                    @if($item->jenis == 'keterlambatan')
                                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-semibold inline-block">
                                            ⏰ Keterlambatan
                                        </span>
                                    @elseif($item->jenis == 'tidak_diambil')
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold inline-block">
                                            🚫 Tidak Diambil
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold inline-block">
                                            📚 Buku Rusak
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-2 border text-xs text-gray-600" data-label="Keterangan">
                                <div class="nilai">{{ $item->keterangan ?? '-' }}</div>
                            </td>

                            <td class="px-4 py-2 border" data-label="Bukti">
                                <div class="nilai">
                                @if($item->bukti_sanksi)
                                    <button onclick="document.getElementById('modal-sanksi-{{ $item->id }}').classList.remove('hidden')"
                                            class="text-blue-600 hover:underline text-xs">
                                        Lihat Bukti
                                    </button>
                                    <div id="modal-sanksi-{{ $item->id }}"
                                         class="hidden fixed inset-0 z-50 flex items-center justify-center">
                                        <div class="absolute inset-0 bg-white/30 backdrop-blur-sm"
                                             onclick="document.getElementById('modal-sanksi-{{ $item->id }}').classList.add('hidden')">
                                        </div>
                                        <div class="relative z-10 rounded-xl overflow-hidden shadow-2xl max-w-lg w-full mx-4">
                                            <img src="{{ asset('storage/'.$item->bukti_sanksi) }}"
                                                 class="w-full object-contain max-h-[80vh]"
                                                 alt="Bukti Sanksi">
                                            <button onclick="document.getElementById('modal-sanksi-{{ $item->id }}').classList.add('hidden')"
                                                    class="absolute top-2 right-2 bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow hover:bg-gray-100 font-bold text-sm">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                @elseif($item->jenis == 'tidak_diambil')
                                    <span class="text-gray-400 text-xs">-</span>
                                @else
                                    <span class="text-gray-400 text-xs">Belum upload</span>
                                @endif
                                </div>
                            </td>

                            <td class="px-4 py-2 border" data-label="Status">
                                <div class="nilai">
                                @php
                                    $statusColor = [
                                        'menunggu'             => 'bg-yellow-100 text-yellow-700',
                                        'menunggu_verifikasi'  => 'bg-orange-100 text-orange-700',
                                        'selesai'              => 'bg-green-100 text-green-700',
                                        'ditolak'              => 'bg-red-100 text-red-700',
                                    ][$item->status] ?? 'bg-gray-100 text-gray-700';
                                    $statusLabel = [
                                        'menunggu'             => 'Menunggu Bukti',
                                        'menunggu_verifikasi'  => 'Menunggu Verifikasi',
                                        'selesai'              => 'Selesai',
                                        'ditolak'              => 'Ditolak',
                                    ][$item->status] ?? $item->status;
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold inline-block {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                                </div>
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="aksi-cell px-4 py-2 border">
                                @if($item->status == 'menunggu_verifikasi')
                                    <form action="{{ route('pengurus.sanksi.konfirmasi', $item) }}"
                                          method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn-aksi bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                            Konfirmasi
                                        </button>
                                    </form>
                                    <form action="{{ route('pengurus.sanksi.tolak', $item) }}"
                                          method="POST" class="inline ml-1">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn-aksi bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600"
                                            onclick="return confirm('Tolak bukti sanksi ini?')">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="sel-kosong text-center px-4 py-4 text-gray-500">
                                @if(request()->filled('search') || request()->filled('status') || request()->filled('jenis'))
                                    Tidak ada sanksi yang cocok dengan pencarian.
                                @else
                                    Belum ada data sanksi
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="mt-4">{{ $sanksi->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>