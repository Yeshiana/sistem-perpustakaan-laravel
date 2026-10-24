<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Manajemen Peminjaman
        </h2>
    </x-slot>

    {{-- ====== CSS RESPONSIF TABEL (HP: tabel berubah menjadi kartu) ====== --}}
    <style>
        @media (max-width: 767px) {
            table.tabel-peminjaman,
            table.tabel-peminjaman tbody {
                display: block;
                border: 0;
            }

            table.tabel-peminjaman thead {
                display: none;
            }

            /* Setiap baris menjadi satu kartu */
            table.tabel-peminjaman tr {
                display: block;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                padding: 0.5rem 0.875rem;
                margin-bottom: 0.75rem;
            }

            table.tabel-peminjaman tr.hidden {
                display: none;
            }

            table.tabel-peminjaman tr.grup-header {
                border-color: #d8b4fe;
            }

            /* Setiap sel menjadi baris "label : nilai" */
            table.tabel-peminjaman td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                border: 0;
                padding: 0.375rem 0;
            }

            table.tabel-peminjaman td[data-label]::before {
                content: attr(data-label);
                flex-shrink: 0;
                font-size: 0.75rem;
                font-weight: 600;
                color: #6b7280;
                padding-top: 0.125rem;
            }

            table.tabel-peminjaman td .nilai {
                text-align: right;
                min-width: 0;
                overflow-wrap: anywhere;
            }

            table.tabel-peminjaman td.sembunyi-mobile {
                display: none;
            }

            table.tabel-peminjaman td.sel-kosong {
                justify-content: center;
            }

            /* Kolom aksi: tombol disusun vertikal dan selebar kartu */
            table.tabel-peminjaman td.aksi-cell {
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                gap: 0.5rem;
                margin-top: 0.25rem;
                padding-top: 0.75rem;
                border-top: 1px solid #f3f4f6;
            }

            table.tabel-peminjaman td.aksi-cell > .btn-aksi,
            table.tabel-peminjaman td.aksi-cell > form {
                display: block;
                width: 100%;
                margin: 0;
            }

            table.tabel-peminjaman td.aksi-cell .btn-aksi {
                width: 100%;
                padding: 0.625rem 0.75rem;
                font-size: 0.875rem;
                text-align: center;
            }
        }
    </style>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4 text-sm">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg rounded-lg p-4 sm:p-6">

                {{-- TOMBOL PEMINJAMAN BUKU PAKET & OFFLINE --}}
                <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:justify-between sm:items-center">
                    <a href="{{ route('pengurus.peminjaman-paket.index') }}"
                       class="w-full sm:w-auto justify-center bg-white border border-indigo-200 text-indigo-600 px-4 py-2.5 sm:py-2 rounded hover:bg-indigo-50 text-sm font-medium inline-flex items-center gap-2 transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Lihat Semua Peminjaman Buku Paket
                    </a>

                    <div class="grid grid-cols-2 gap-2 sm:flex">
                        <a href="{{ route('pengurus.peminjaman-paket.create') }}"
                           class="flex items-center justify-center text-center bg-indigo-600 text-white px-3 sm:px-4 py-2.5 sm:py-2 rounded hover:bg-indigo-700 text-sm font-medium">
                            + Pinjam Buku Paket
                        </a>
                        <a href="{{ route('pengurus.peminjaman.offline.create') }}"
                           class="flex items-center justify-center text-center bg-blue-600 text-white px-3 sm:px-4 py-2.5 sm:py-2 rounded hover:bg-blue-700 text-sm font-medium">
                            + Pinjam Offline
                        </a>
                    </div>
                </div>
                {{-- BATAS BUKU PAKET --}}

                {{-- FORM PENCARIAN & FILTER --}}
                <form method="GET" class="flex flex-col gap-2 mb-4 sm:flex-row sm:flex-wrap sm:gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / judul buku..."
                           class="border rounded px-3 py-2 w-full sm:w-64 text-base sm:text-sm">
                    <select name="status" class="border rounded px-3 py-2 w-full sm:w-auto text-base sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                        <option value="menunggu_konfirmasi" {{ request('status')=='menunggu_konfirmasi'?'selected':'' }}>Menunggu Konfirmasi</option>
                        <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                        <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                        <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                        <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                    </select>
                    <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                        <button type="submit" class="bg-gray-600 text-white px-4 py-2.5 sm:py-2 rounded text-sm">Cari</button>
                        <a href="{{ route('pengurus.peminjaman.index') }}"
                           class="block text-center bg-gray-200 text-gray-700 px-4 py-2.5 sm:py-2 rounded text-sm">Reset</a>
                    </div>
                </form>

                {{-- TABEL (desktop) / KARTU (HP) --}}
                <div class="md:overflow-x-auto">
                <table class="tabel-peminjaman w-full text-sm text-left border border-blue-200">
                    <thead class="bg-blue-600">
                        <tr>
                            <th class="px-4 py-2 border border-blue-200 text-white">No</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Peminjam</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Buku</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Tgl Ambil</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Tgl Kembali</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Bukti</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Status</th>
                            <th class="px-4 py-2 border border-blue-200 text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $no = 0; @endphp
                    @forelse($peminjaman as $item)
                    @php
                        $info = $rowspanInfo[$item->id] ?? ['is_first' => true, 'rowspan' => 1, 'kode_transaksi' => null, 'group_key' => null];
                        if ($info['rowspan'] == 1 || $info['is_first']) {
                            $no++;
                        }
                    @endphp

                    {{-- Baris header grup (klik untuk membuka detail) --}}
                    @if($info['rowspan'] > 1 && $info['is_first'])
                    <tr class="grup-header cursor-pointer hover:bg-purple-50 bg-purple-50/40 border-t-2 border-t-purple-200"
                        onclick="toggleGrup('{{ $info['group_key'] }}')">
                        <td class="sembunyi-mobile px-4 py-2 border">{{ $no }}</td>
                        <td class="px-4 py-2 border" colspan="7">
                            <span class="inline-flex items-start md:items-center gap-2 text-purple-700 font-medium text-sm">
                                <svg id="chevron-{{ $info['group_key'] }}" class="w-4 h-4 mt-0.5 md:mt-0 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>
                                    {{ $item->user->name }} ({{ ucfirst($item->user->role) }}) — 📦 {{ $info['rowspan'] }} buku dipinjam. Klik untuk lihat detail.
                                </span>
                            </span>
                        </td>
                    </tr>
                    @endif

                    <tr class="{{ $info['rowspan'] > 1 ? 'grup-'.$info['group_key'].' hidden bg-gray-50' : '' }}">
                        <td class="sembunyi-mobile px-4 py-2 border">{{ $info['rowspan'] > 1 ? '' : $no }}</td>

                        <td class="px-4 py-2 border" data-label="Peminjam">
                            <div class="nilai">
                                {{ $item->user->name }}
                                <span class="text-xs text-gray-500">({{ ucfirst($item->user->role) }})</span>
                            </div>
                        </td>

                        <td class="px-4 py-2 border" data-label="Buku">
                            <div class="nilai">{{ $item->buku->judul }}</div>
                        </td>

                        <td class="px-4 py-2 border" data-label="Tgl Ambil">
                            <div class="nilai">
                                @if($item->tgl_ambil_fix)
                                    {{ $item->tgl_ambil_fix->format('d M Y') }}
                                @elseif($item->tgl_ambil)
                                    <span class="text-gray-400 italic">{{ $item->tgl_ambil->format('d M Y') }} (rencana)</span>
                                @else
                                    -
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-2 border" data-label="Tgl Kembali">
                            <div class="nilai">
                                {{ $item->tgl_kembali ? $item->tgl_kembali->format('d M Y') : '-' }}
                            </div>
                        </td>

                        <td class="px-4 py-2 border" data-label="Bukti">
                            <div class="nilai">
                            @if($item->bukti_pengambilan)
                                {{-- Tombol trigger --}}
                                <button onclick="document.getElementById('modal-bukti-{{ $item->id }}').classList.remove('hidden')"
                                        class="text-blue-600 hover:underline text-xs">
                                    Lihat Bukti
                                </button>

                                {{-- Modal --}}
                                <div id="modal-bukti-{{ $item->id }}"
                                     class="hidden fixed inset-0 z-50 flex items-center justify-center">
                                    {{-- Overlay blur --}}
                                    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm"
                                         onclick="document.getElementById('modal-bukti-{{ $item->id }}').classList.add('hidden')">
                                    </div>
                                    {{-- Konten foto --}}
                                    <div class="relative z-10 rounded-xl overflow-hidden shadow-2xl max-w-lg w-full mx-4">
                                        <img src="{{ asset('storage/'.$item->bukti_pengambilan) }}"
                                             class="w-full object-contain max-h-[80vh]"
                                             alt="Bukti Pengambilan">
                                        <button onclick="document.getElementById('modal-bukti-{{ $item->id }}').classList.add('hidden')"
                                                class="absolute top-2 right-2 bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow hover:bg-gray-100 font-bold text-sm">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                            </div>
                        </td>

                        <td class="px-4 py-2 border" data-label="Status">
                            <div class="nilai">
                            @if($item->status == 'selesai' && $item->daftarSanksi->isNotEmpty())
                                <div class="flex flex-col gap-1 items-end md:items-start">
                                    @foreach($item->daftarSanksi as $sanksi)
                                        @php
                                            $labelJenis = match($sanksi->jenis) {
                                                'buku_rusak'    => 'Buku Rusak',
                                                'tidak_diambil' => 'Tidak Diambil',
                                                default         => 'Keterlambatan',
                                            };
                                            $statusSanksi = match($sanksi->status) {
                                                'menunggu' => ['label' => 'Menunggu Bukti: '.$labelJenis, 'warna' => 'bg-yellow-100 text-yellow-700'],
                                                'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi: '.$labelJenis, 'warna' => 'bg-orange-100 text-orange-700'],
                                                'selesai' => ['label' => 'Selesai (Sanksi '.$labelJenis.')', 'warna' => 'bg-green-100 text-green-700'],
                                                'ditolak' => ['label' => 'Bukti Ditolak: '.$labelJenis, 'warna' => 'bg-red-100 text-red-700'],
                                                default => ['label' => $labelJenis, 'warna' => 'bg-gray-100 text-gray-700'],
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusSanksi['warna'] }} inline-block w-fit">
                                            {{ $statusSanksi['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                @php
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
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            @endif
                            </div>
                        </td>

                        {{-- KOLOM AKSI --}}
                        <td class="aksi-cell px-4 py-2 border">
                            @if($item->status == 'menunggu')
                                <button onclick="document.getElementById('modal-approve-{{ $item->id }}').classList.remove('hidden')"
                                    class="btn-aksi bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                    Setujui
                                </button>

                                <div id="modal-approve-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center">
                                    <div class="absolute inset-0 bg-black/40"
                                         onclick="document.getElementById('modal-approve-{{ $item->id }}').classList.add('hidden')"></div>
                                    <div class="relative z-10 bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4 text-left">
                                        <h3 class="font-bold text-gray-800 mb-1">Tentukan Tanggal Ambil</h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            Buku "<span class="font-semibold">{{ $item->buku->judul }}</span>" —
                                            rencana ambil siswa: {{ $item->tgl_ambil?->format('d M Y') }}
                                        </p>
                                        <form action="{{ route('pengurus.peminjaman.approve', $item) }}" method="POST">
                                            @csrf @method('PATCH')

                                            <label class="block text-xs text-gray-500 mb-1">Tanggal ambil fix (wajib diambil di tanggal ini)</label>
                                            <input type="date" name="tgl_ambil_fix"
                                                   value="{{ $item->tgl_ambil?->format('Y-m-d') }}"
                                                   min="{{ today()->format('Y-m-d') }}"
                                                   class="border rounded px-3 py-2 w-full text-sm mb-3 tgl-ambil-fix" required
                                                   oninput="cekWeekend(this)">
                                            <p class="hidden text-xs text-red-500 mb-2 pesan-weekend">
                                                Tanggal ini Sabtu/Minggu — perpustakaan tutup, pilih tanggal lain.
                                            </p>
                                            <div class="flex gap-2">
                                                <button type="submit" class="flex-1 bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600">
                                                    Setujui
                                                </button>
                                                <button type="button"
                                                        onclick="document.getElementById('modal-approve-{{ $item->id }}').classList.add('hidden')"
                                                        class="flex-1 bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-300">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <form action="{{ route('pengurus.peminjaman.tolak', $item) }}"
                                      method="POST" class="inline mt-1">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="btn-aksi bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600"
                                        onclick="return confirm('Tolak peminjaman ini?')">
                                        Tolak
                                    </button>
                                </form>

                            @elseif($item->status == 'disetujui')
                                <form action="{{ route('pengurus.peminjaman.konfirmasi-ambil', $item) }}"
                                      method="POST" enctype="multipart/form-data" class="space-y-1">
                                    @csrf @method('PATCH')
                                    <input type="file" name="bukti_pengambilan"
                                           accept="image/*"
                                           onchange="cekUkuranFile(this)"
                                           class="text-xs w-full" required>
                                    <p class="hidden text-xs text-red-500 pesan-ukuran-file">
                                        Ukuran file maksimal 2MB. Pilih file lain.
                                    </p>
                                    <button type="submit"
                                        class="btn-aksi w-full bg-indigo-500 text-white px-2 py-1 rounded text-xs hover:bg-indigo-600">
                                        Konfirmasi & Upload Bukti
                                    </button>
                                    <p class="text-[10px] text-gray-400 text-center">Maks. 2MB (JPG/PNG)</p>
                                </form>

                            @elseif($item->status == 'menunggu_konfirmasi')
                                <span class="text-orange-600 text-xs font-semibold">Menunggu Konfirmasi</span>

                            @elseif($item->status == 'dipinjam')
                                <button onclick="document.getElementById('modal-kembali-{{ $item->id }}').classList.remove('hidden')"
                                        class="btn-aksi bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                    Konfirmasi Kembali
                                </button>

                                {{-- Modal Konfirmasi --}}
                                <div id="modal-kembali-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center">
                                    <div class="absolute inset-0 bg-black/40"
                                         onclick="document.getElementById('modal-kembali-{{ $item->id }}').classList.add('hidden')"></div>
                                    <div class="relative z-10 bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4 text-left">
                                        <h3 class="font-bold text-gray-800 mb-1">Konfirmasi Pengembalian</h3>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Buku "<span class="font-semibold">{{ $item->buku->judul }}</span>" apakah dalam kondisi baik?
                                        </p>
                                        <div class="flex gap-2">
                                            <form action="{{ route('pengurus.peminjaman.kembalikan', $item) }}" method="POST" class="flex-1">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="kondisi" value="baik">
                                                <button type="submit"
                                                    class="w-full bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600">
                                                    ✅ Baik
                                                </button>
                                            </form>
                                            <form action="{{ route('pengurus.peminjaman.kembalikan', $item) }}" method="POST" class="flex-1">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="kondisi" value="rusak">
                                                <button type="submit"
                                                    class="w-full bg-red-500 text-white px-3 py-2 rounded text-sm hover:bg-red-600"
                                                    onclick="return confirm('Buku rusak? Sanksi akan dibuat otomatis.')">
                                                    ❌ Rusak
                                                </button>
                                            </form>
                                        </div>
                                        <button onclick="document.getElementById('modal-kembali-{{ $item->id }}').classList.add('hidden')"
                                                class="mt-3 text-xs text-gray-400 hover:text-gray-600 w-full text-center">
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
                        <td colspan="8" class="sel-kosong text-center px-4 py-4 text-gray-500">
                            Belum ada data peminjaman
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>

                <div class="mt-4">{{ $peminjaman->links() }}</div>
            </div>
        </div>
    </div>

<script>
function toggleGrup(key) {
    document.querySelectorAll('.grup-' + key).forEach(function (row) {
        row.classList.toggle('hidden');
    });
    var chevron = document.getElementById('chevron-' + key);
    if (chevron) chevron.classList.toggle('rotate-90');
}

function cekUkuranFile(input) {
    var maxBytes = 2 * 1024 * 1024; // 2MB
    var pesan = input.closest('form').querySelector('.pesan-ukuran-file');
    var tombolSubmit = input.closest('form').querySelector('button[type="submit"]');

    if (input.files.length > 0 && input.files[0].size > maxBytes) {
        pesan.classList.remove('hidden');
        input.value = ''; // reset input, file tidak jadi terpilih
        if (tombolSubmit) tombolSubmit.disabled = true;
    } else {
        pesan.classList.add('hidden');
        if (tombolSubmit) tombolSubmit.disabled = false;
    }
}

function cekWeekend(input) {
    var tanggal = new Date(input.value + 'T00:00:00');
    var hari = tanggal.getDay(); // 0 = Minggu, 6 = Sabtu
    var pesan = input.closest('form').querySelector('.pesan-weekend');
    var tombolSubmit = input.closest('form').querySelector('button[type="submit"]');

    if (hari === 0 || hari === 6) {
        pesan.classList.remove('hidden');
        input.setCustomValidity('Tanggal ambil fix tidak boleh Sabtu/Minggu');
        if (tombolSubmit) tombolSubmit.disabled = true;
    } else {
        pesan.classList.add('hidden');
        input.setCustomValidity('');
        if (tombolSubmit) tombolSubmit.disabled = false;
    }
}
</script>
</x-app-layout>