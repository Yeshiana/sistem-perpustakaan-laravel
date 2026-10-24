<x-app-layout>

<style>
@media print {
    body * { visibility: hidden; }
    #area-cetak, #area-cetak * { visibility: visible; }
    #area-cetak {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 0;
        margin: 0;
    }
    .no-print { display: none !important; }
}
</style>

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Laporan Perpustakaan
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-start gap-2 text-sm no-print">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- KIRIM LAPORAN --}}
            <div class="bg-white rounded-xl shadow p-4 sm:p-6 no-print">
                <div class="flex items-start gap-3 mb-5">
                    <div class="w-10 h-10 shrink-0 bg-sky-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-800 text-base leading-snug">Kirim Laporan ke Kepala Perpustakaan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Upload file laporan untuk disetujui kepala</p>
                    </div>
                </div>

                <form action="{{ route('pengurus.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan</label>
                        <input type="text" name="judul" required placeholder="Contoh: Laporan Bulanan Juli 2026"
                               class="w-full h-11 border border-gray-200 rounded-xl px-3 sm:px-4 text-base sm:text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan untuk kepala..."
                                  class="w-full border border-gray-200 rounded-xl px-3 sm:px-4 py-2.5 text-base sm:text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Laporan</label>
                        <p class="text-xs text-gray-400 mb-1.5">PDF, Word, Excel — maks 5MB</p>
                        <input type="file" name="file_laporan" required accept=".pdf,.doc,.docx,.xlsx"
                               class="block w-full border border-gray-200 rounded-xl text-sm text-gray-600 overflow-hidden
                                      file:mr-3 file:h-11 file:px-4 file:border-0 file:bg-sky-50 file:text-sky-600 file:font-medium">
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto h-12 sm:h-auto bg-sky-600 hover:bg-sky-700 active:scale-[.98] text-white px-6 sm:py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Laporan
                    </button>
                </form>

                {{-- Riwayat laporan terkirim --}}
                @if($laporanTerkirim->isNotEmpty())
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-500 mb-3">Riwayat Laporan Terkirim</h4>
                    <div class="space-y-2">
                        @foreach($laporanTerkirim as $lap)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-2.5 px-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center
                                    {{ $lap->status == 'menunggu' ? 'bg-yellow-50' : ($lap->status == 'disetujui' ? 'bg-green-50' : 'bg-red-50') }}">
                                    <svg class="w-4 h-4 {{ $lap->status == 'menunggu' ? 'text-yellow-500' : ($lap->status == 'disetujui' ? 'text-green-500' : 'text-red-500') }}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 break-words">{{ $lap->judul }}</p>
                                    <p class="text-xs text-gray-400">{{ $lap->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 pl-11 sm:pl-0 shrink-0">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $lap->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' :
                                       ($lap->status == 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($lap->status) }}
                                </span>
                                <a href="{{ asset('storage/'.$lap->file_laporan) }}" target="_blank"
                                   class="text-xs text-sky-600 hover:underline py-1">Lihat File</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- FILTER --}}
            <div class="bg-white rounded-xl shadow p-4 sm:p-6 no-print">
                <form method="GET" class="grid grid-cols-2 gap-3 sm:flex sm:gap-4 sm:flex-wrap sm:items-end">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                        <select name="jenis_laporan" class="w-full h-11 sm:h-auto border rounded-lg px-3 py-2 text-base sm:text-sm">
                            <option value="kunjungan" {{ $jenisLaporan == 'kunjungan' ? 'selected' : '' }}>Laporan Kunjungan</option>
                            <option value="peminjaman" {{ $jenisLaporan == 'peminjaman' ? 'selected' : '' }}>Laporan Peminjaman</option>
                            <option value="buku" {{ $jenisLaporan == 'buku' ? 'selected' : '' }}>Laporan Koleksi Buku</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Filter</label>
                        <select name="filter_type" onchange="this.form.submit()"
                                class="w-full h-11 sm:h-auto border rounded-lg px-3 py-2 text-base sm:text-sm">
                            <option value="bulan" {{ $filterType == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                            <option value="tahun" {{ $filterType == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                        </select>
                    </div>
                    @if($filterType == 'bulan')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" class="w-full h-11 sm:h-auto border rounded-lg px-3 py-2 text-base sm:text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" class="w-full h-11 sm:h-auto border rounded-lg px-3 py-2 text-base sm:text-sm">
                            @foreach(range(2024, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1 grid grid-cols-2 gap-3 sm:flex sm:gap-4">
                        <button type="submit"
                                class="h-11 sm:h-auto bg-sky-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-sky-700">
                            Tampilkan
                        </button>
                        <button type="button" onclick="window.print()"
                                class="h-11 sm:h-auto bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
                            🖨️ Cetak
                        </button>
                    </div>
                </form>
            </div>

            <div id="area-cetak" class="space-y-4 sm:space-y-6">

                {{-- KOP SURAT --}}
                <div class="bg-white rounded-xl shadow p-4 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 border-b-4 border-gray-800 pb-4 mb-4">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20 object-contain shrink-0">
                        <div class="text-center flex-1 min-w-0">
                            <h1 class="text-base sm:text-lg font-bold text-gray-800 uppercase tracking-wide leading-snug">
                                Perpustakaan SMK Negeri 1 Bojongpicung
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600">
                                Jl. Raya Bojongpicung, Kabupaten Cianjur, Provinsi Jawa Barat
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600 break-words">
                                Email: perpustakaan@smkn1bojongpicung.sch.id
                            </p>
                        </div>
                    </div>

                    <div class="text-center mb-2">
                        @php
                            $labelJenis = match($jenisLaporan) {
                                'kunjungan'  => 'Kunjungan',
                                'peminjaman' => 'Peminjaman',
                                'buku'       => 'Koleksi Buku',
                                default      => '',
                            };
                        @endphp
                        <h2 class="text-sm sm:text-base font-bold text-gray-800 uppercase underline leading-snug">
                            Laporan {{ $filterType == 'bulan' ? 'Bulanan' : 'Tahunan' }} {{ $labelJenis }} Perpustakaan
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Periode:
                            @if($filterType == 'bulan')
                                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                            @else
                                Tahun {{ $tahun }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- REKAP KUNJUNGAN --}}
                @if($jenisLaporan == 'kunjungan')
                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-4 flex items-center gap-2.5">
                        <span class="w-8 h-8 bg-sky-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Rekap Kunjungan
                    </h3>

                    <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                        <table class="w-full min-w-[480px] text-xs sm:text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 sm:px-3 py-2 border text-center">No</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Nama</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Kelas</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Jenis</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Keperluan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kunjunganDetail as $i => $kj)
                                <tr>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $i + 1 }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $kj->nama_pengunjung }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $kj->kelas_pengunjung ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ ucfirst($kj->jenis_pengunjung) }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ ucfirst(str_replace('_',' ',$kj->tujuan)) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-3 py-4 border text-center text-gray-400">Belum ada data kunjungan periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="4" class="px-2 sm:px-3 py-2 border text-right">Total Kunjungan</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $kunjunganDetail->count() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                {{-- REKAP PEMINJAMAN --}}
                @if($jenisLaporan == 'peminjaman')
                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-4 flex items-center gap-2.5">
                        <span class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Rekap Peminjaman
                    </h3>

                    <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                        <table class="w-full min-w-[640px] text-xs sm:text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 sm:px-3 py-2 border text-center">No</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Nama Peminjam</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Judul Buku</th>
                                    <th class="px-2 sm:px-3 py-2 border text-center">Tgl Pengambilan</th>
                                    <th class="px-2 sm:px-3 py-2 border text-center">Tgl Pengembalian</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Sanksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanDetail as $i => $p)
                                <tr>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $i + 1 }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $p->user->name ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $p->buku->judul ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center whitespace-nowrap">{{ $p->tgl_ambil ? $p->tgl_ambil->format('d-m-Y') : '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center whitespace-nowrap">{{ $p->tgl_kembali ? $p->tgl_kembali->format('d-m-Y') : '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $p->sanksi->jenis ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-3 py-4 border text-center text-gray-400">Belum ada data peminjaman periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="5" class="px-2 sm:px-3 py-2 border text-right">Total Peminjaman</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $peminjamanDetail->count() }}</td>
                                </tr>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="5" class="px-2 sm:px-3 py-2 border text-right">Total Kena Sanksi</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $peminjamanDetail->whereNotNull('sanksi')->count() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                {{-- REKAP KOLEKSI BUKU --}}
                @if($jenisLaporan == 'buku')
                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-4 flex items-center gap-2.5">
                        <span class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.966 8.966 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </span>
                        Rekap Koleksi Buku
                    </h3>

                    <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                        <table class="w-full min-w-[420px] text-xs sm:text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 sm:px-3 py-2 border text-center">No</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Judul Buku</th>
                                    <th class="px-2 sm:px-3 py-2 border text-left">Kategori</th>
                                    <th class="px-2 sm:px-3 py-2 border text-center">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bukuDetail as $i => $b)
                                <tr>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $i + 1 }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $b->judul }}</td>
                                    <td class="px-2 sm:px-3 py-2 border">{{ $b->kategori->nama ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $b->stok }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-3 py-4 border text-center text-gray-400">Belum ada data buku.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="3" class="px-2 sm:px-3 py-2 border text-right">Total</td>
                                    <td class="px-2 sm:px-3 py-2 border text-center">{{ $bukuDetail->sum('stok') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                {{-- GRAFIK --}}
                @if($jenisLaporan == 'kunjungan' || $jenisLaporan == 'peminjaman')
                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-4 flex items-center gap-2.5">
                        <span class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </span>
                        <span class="min-w-0">Grafik Kunjungan &amp; Peminjaman</span>
                    </h3>

                    {{-- Tinggi tetap supaya grafik tidak gepeng di HP --}}
                    <div class="relative h-64 sm:h-80">
                        <canvas id="grafikChart"></canvas>
                    </div>
                </div>
                @endif

                {{-- TANDA TANGAN --}}
                <div class="bg-white rounded-xl shadow p-4 sm:p-8">
                    <p class="text-sm text-gray-600 mb-8 sm:mb-10">
                        Bojongpicung,
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </p>
                    <div class="grid grid-cols-2 gap-4 sm:gap-8 text-center text-xs sm:text-sm">
                        <div>
                            <p class="text-gray-600 mb-14 sm:mb-16">Dibuat oleh,<br>Pengurus Perpustakaan</p>
                            <p class="font-bold text-gray-800 underline break-words">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-14 sm:mb-16">Mengetahui,<br>Kepala Perpustakaan</p>
                            <p class="font-bold text-gray-800 underline break-words">
                                {{ $kepalaPerpus->name ?? '(...........................)' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            {{-- /area-cetak --}}

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($grafikKunjungan->pluck('tgl')) !!};
        const kunjunganData = {!! json_encode($grafikKunjungan->pluck('total')) !!};
        const peminjamanData = {!! json_encode($grafikPeminjaman->pluck('total')) !!};

        const canvasGrafik = document.getElementById('grafikChart');
        if (canvasGrafik) {
            new Chart(canvasGrafik, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Kunjungan',
                            data: kunjunganData,
                            backgroundColor: 'rgba(14, 165, 233, 0.7)',
                            borderColor: 'rgba(14, 165, 233, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Peminjaman',
                            data: peminjamanData,
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        x: { ticks: { maxRotation: 60, autoSkip: true } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script>
</x-app-layout>