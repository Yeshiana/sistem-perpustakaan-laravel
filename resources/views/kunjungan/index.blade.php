<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kunjungan Perpustakaan</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-sky-900 via-sky-800 to-indigo-900 min-h-screen">

    <div class="min-h-screen flex flex-col justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-md mx-auto">

            {{-- Judul --}}
            <div class="text-center mb-6 sm:mb-8">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center text-2xl sm:text-3xl mx-auto mb-3 sm:mb-4 shadow-lg">
                    📚
                </div>
                <h1 class="text-white text-xl sm:text-2xl font-bold leading-tight">Buku Tamu Perpustakaan</h1>
                <p class="text-sky-300 text-sm mt-1">SMK Negeri 1 Bojongpicung</p>
            </div>

            {{-- Kartu form --}}
            <div class="bg-white rounded-2xl shadow-2xl p-5 sm:p-8">
                <h2 class="text-gray-800 font-bold text-lg mb-1">Catat kunjunganmu</h2>
                <p class="text-gray-400 text-xs mb-5 sm:mb-6">Isi data singkat di bawah ini</p>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('kunjungan.simpan') }}" class="space-y-4 sm:space-y-5">
                    @csrf

                    <input type="hidden" name="anggota_id" id="anggota_id">

                    {{-- Nama + saran otomatis --}}
                    <div class="relative">
                        <label for="nama_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="nama_pengunjung" name="nama_pengunjung" required autofocus
                               autocomplete="off"
                               value="{{ old('nama_pengunjung', auth()->user()->name ?? '') }}"
                               placeholder="Masukkan nama kamu"
                               class="w-full box-border border border-gray-300 rounded-xl px-4 py-3 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">

                        <ul id="saranNama"
                            class="hidden absolute z-20 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                        </ul>
                        <p class="text-xs text-gray-400 mt-1.5">Anggota: ketik nama, lalu pilih dari daftar yang muncul.</p>
                    </div>

                    @guest
                    <div>
                        <label for="kelas_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kelas / Asal instansi <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" id="kelas_pengunjung" name="kelas_pengunjung"
                               value="{{ old('kelas_pengunjung') }}"
                               placeholder="Contoh: XI TKJ 1 / nama sekolah"
                               class="w-full box-border border border-gray-300 rounded-xl px-4 py-3 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    @endguest

                    {{-- BARU: Keperluan dengan keterangan --}}
                    <div>
                        <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-1.5">Keperluan</label>
                        <select id="tujuan" name="tujuan" required
                                class="w-full box-border border border-gray-300 rounded-xl px-4 py-3 text-base sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            <option value="" disabled selected>Pilih keperluan</option>
                            <option value="Membaca">Membaca</option>
                            <option value="Mengerjakan Tugas">Mengerjakan Tugas</option>
                            <option value="Peminjaman Buku">Peminjaman Buku</option>
                            <option value="Pengembalian Buku">Pengembalian Buku</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <p id="infoTujuan" class="hidden text-xs text-gray-400 mt-1.5">
                            Pengunjung non-anggota hanya dapat membaca di tempat.
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full bg-sky-700 hover:bg-sky-800 active:bg-sky-900 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
                        Catat Kunjungan
                    </button>
                </form>
            </div>

            <div class="text-center mt-6 space-y-1">
                <p class="text-sky-300 text-xs">
                    © {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung
                </p>
                <p class="text-sky-400 text-xs">
                    Developed by <span class="font-semibold text-sky-200">Yeshiana</span> © 2026
                </p>
            </div>
        </div>
    </div>

<script>
(function () {
    const input = document.getElementById('nama_pengunjung');
    const list  = document.getElementById('saranNama');
    const idAnggota = document.getElementById('anggota_id');
    const url   = @json(route('kunjungan.cari-anggota'));
    let timer = null;
    let controller = null;

    // BARU: kunci Keperluan untuk non-anggota
    const tujuan = document.getElementById('tujuan');
    const infoTujuan = document.getElementById('infoTujuan');
    const sudahLogin = @json(auth()->check());

    function aturTujuan() {
        const anggota = sudahLogin || idAnggota.value !== '';

        if (anggota) {
            Array.from(tujuan.options).forEach(o => o.disabled = (o.value === ''));
            infoTujuan.classList.add('hidden');
            if (tujuan.dataset.otomatis === '1') {
                tujuan.value = '';
                tujuan.dataset.otomatis = '';
            }
        } else {
            Array.from(tujuan.options).forEach(o => o.disabled = (o.value !== 'Membaca'));
            tujuan.value = 'Membaca';
            tujuan.dataset.otomatis = '1';
            infoTujuan.classList.remove('hidden');
        }
    }
    aturTujuan();

    function tutup() {
        list.classList.add('hidden');
        list.innerHTML = '';
    }

    function tampil(daftar) {
        list.innerHTML = '';
        if (!daftar.length) return tutup();

        daftar.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'px-4 py-3 text-sm text-gray-700 cursor-pointer active:bg-sky-100 hover:bg-sky-50 border-b border-gray-100 last:border-0';
            li.innerHTML = '<span class="font-medium"></span><span class="text-xs text-gray-400 ml-2"></span>';
            li.children[0].textContent = item.nama;
            li.children[1].textContent = item.kelas || '';

            function pilih(e) {
                e.preventDefault();
                input.value = item.nama;
                idAnggota.value = item.id;
                const kelas = document.getElementById('kelas_pengunjung');
                if (kelas && item.kelas) kelas.value = item.kelas;
                aturTujuan(); // BARU
                tutup();
            }
            li.addEventListener('mousedown', pilih);
            li.addEventListener('touchstart', pilih, { passive: false });
            list.appendChild(li);
        });
        list.classList.remove('hidden');
    }

    input.addEventListener('input', function () {
        idAnggota.value = '';
        aturTujuan(); // BARU

        const q = input.value.trim();
        clearTimeout(timer);

        if (q.length < 3) return tutup();

        timer = setTimeout(function () {
            if (controller) controller.abort();
            controller = new AbortController();

            fetch(url + '?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json' },
                signal: controller.signal
            })
            .then(r => r.ok ? r.json() : [])
            .then(tampil)
            .catch(function () {});
        }, 250);
    });

    input.addEventListener('blur', function () { setTimeout(tutup, 150); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') tutup();
    });
})();
</script>

</body>
</html>