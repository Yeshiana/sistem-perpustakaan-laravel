<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan SMK Negeri 1 Bojongpicung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
   @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
   
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js'])
</head> --}}
<body class="font-sans antialiased">

    {{-- TOP BAR --}}
    <div class="bg-sky-900 text-sky-200 text-xs py-2 px-4 hidden sm:block">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex gap-6">
                <span>📞 (0263) 123-4567</span>
                <span>✉️ perpustakaan@smkn1bojongpicung.sch.id</span>
                <span>🕐 Sen - Jum: 07:00 - 15:00</span>
            </div>
            <a href="{{ route('register.pilih') }}" class="text-sky-200 hover:text-white">
                Belum punya akun? Daftar di sini →
            </a>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}"
                         class="w-10 h-10 rounded-full object-cover border-2 border-sky-200 shadow">
                    <div>
                        <p class="text-sky-900 font-bold text-sm leading-tight">SMK Negeri 1 Bojongpicung</p>
                        <p class="text-sky-600 text-xs">Sistem Perpustakaan Digital</p>
                    </div>
                </div>

                {{-- Menu --}}
                <div class="hidden sm:flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="#beranda" class="hover:text-sky-700">Beranda</a>
                    <a href="#fitur" class="hover:text-sky-700">Fitur</a>
                    <a href="#visi-misi" class="hover:text-sky-700">Visi & Misi</a>
                </div>

                {{-- Tombol Login --}}
                <a href="{{ route('login') }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-5 py-2 rounded-lg text-sm transition shadow">
                    LOGIN
                </a>
            </div>
        </div>
    </nav>

        {{-- HERO SECTION --}}
    <div id="beranda" class="relative overflow-hidden min-h-[70vh] sm:min-h-[500px] lg:min-h-[600px]" style="z-index:0;" data-hero>
        {{-- Background foto --}}
        <img src="{{ asset('images/smkgedung.jpeg') }}"
     class="absolute inset-0 w-full h-full object-cover -z-10"
     alt="SMK Negeri 1 Bojongpicung">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-900/80 via-sky-800/50 to-transparent -z-10"></div>
        {{-- Konten kiri bawah --}}
        <div class="absolute bottom-0 left-0 p-5 pt-20 sm:p-16 max-w-2xl z-10">
        <div class="inline-block bg-yellow-400 text-gray-900 text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-1 rounded mb-3 sm:mb-4 tracking-wide">
                📖 PERPUSTAKAAN DIGITAL
            </div>
            <h1 class="text-2xl sm:text-5xl font-bold text-white leading-tight mb-3 sm:mb-4 drop-shadow-lg">
                Selamat Datang Di<br>
                Perpustakaan<br>
                <span class="text-yellow-300">SMKN 1 Bojongpicung</span>
            </h1>
            <p class="text-sky-100 text-sm sm:text-base mb-5 sm:mb-8 leading-relaxed">
                Perpustakaan SMK Negeri 1 Bojongpicung hadir untuk mendukung kegiatan belajar mengajar siswa dan guru melalui sistem peminjaman buku yang modern dan mudah digunakan.
            </p>
            <div class="flex gap-3 sm:gap-4 flex-wrap">
                <a href="{{ route('login') }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-5 sm:px-8 py-2 sm:py-3 rounded-xl transition shadow-lg text-xs sm:text-sm">
                    MASUK KE SISTEM
                </a>
                <a href="{{ route('register.pilih') }}"
                   class="border-2 border-white text-white font-bold px-5 sm:px-8 py-2 sm:py-3 rounded-xl hover:bg-white/20 transition text-xs sm:text-sm">
                    DAFTAR SEKARANG
                </a>
            </div>
        </div>

        {{-- Stats kanan bawah --}}
        <div class="absolute bottom-0 right-0 p-8 hidden lg:flex gap-4 z-10">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 min-w-[100px]">
                <p class="text-2xl font-bold text-white">500+</p>
                <p class="text-sky-200 text-xs mt-1">Koleksi Buku</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 min-w-[100px]">
                <p class="text-2xl font-bold text-white">200+</p>
                <p class="text-sky-200 text-xs mt-1">Anggota Aktif</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 min-w-[100px]">
                <p class="text-2xl font-bold text-white">24/7</p>
                <p class="text-sky-200 text-xs mt-1">Akses Digital</p>
            </div>
        </div>
    </div>

       {{-- FITUR SECTION --}}
    <div id="fitur" class="bg-white py-8 sm:py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-lg sm:text-2xl font-bold text-center text-gray-800 mb-1 sm:mb-2">Fitur Unggulan</h2>
            <p class="text-center text-gray-500 text-xs sm:text-sm mb-5 sm:mb-10">Kemudahan akses perpustakaan dalam genggaman</p>
                    <div class="grid grid-cols-3 gap-2 sm:gap-6">
        <div class="bg-white rounded-2xl p-2 sm:p-7 text-center border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="w-8 h-8 sm:w-14 sm:h-14 bg-sky-100 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-1.5 sm:mb-4">
            <svg class="w-4 h-4 sm:w-7 sm:h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-[10px] sm:text-base mb-1 sm:mb-2 leading-tight">Katalog Digital</h3>
        <p class="hidden sm:block text-gray-500 text-sm leading-relaxed">Cari dan temukan buku yang tersedia dengan mudah dan cepat</p>
    </div>
    <div class="bg-white rounded-2xl p-2 sm:p-7 text-center border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="w-8 h-8 sm:w-14 sm:h-14 bg-indigo-100 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-1.5 sm:mb-4">
            <svg class="w-4 h-4 sm:w-7 sm:h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-[10px] sm:text-base mb-1 sm:mb-2 leading-tight">Peminjaman Online</h3>
        <p class="hidden sm:block text-gray-500 text-sm leading-relaxed">Ajukan peminjaman buku secara online tanpa perlu antri</p>
    </div>
    <div class="bg-white rounded-2xl p-2 sm:p-7 text-center border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="w-8 h-8 sm:w-14 sm:h-14 bg-amber-100 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-1.5 sm:mb-4">
            <svg class="w-4 h-4 sm:w-7 sm:h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-[10px] sm:text-base mb-1 sm:mb-2 leading-tight">Notifikasi Real-time</h3>
        <p class="hidden sm:block text-gray-500 text-sm leading-relaxed">Pantau status peminjaman, persetujuan, dan sanksi langsung dari sistem</p>
    </div>

</div>
        </div>
    </div>

       {{-- CAROUSEL KOLEKSI BUKU --}}
    @if($bukuCarousel->count() > 0)
    <div class="bg-white py-8 sm:py-14 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 mb-5 sm:mb-8 text-center">
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Koleksi Buku Kami</h2>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Sebagian dari ribuan buku yang tersedia di perpustakaan</p>
        </div>

        <div class="relative">
            <div class="flex gap-4 sm:gap-5 animate-buku-scroll w-max">
                @foreach($bukuCarousel->concat($bukuCarousel) as $buku)
                    <div class="w-28 sm:w-40 shrink-0">
                        <img src="{{ asset('storage/'.$buku->cover) }}"
                             alt="{{ $buku->judul }}"
                             class="w-full h-40 sm:h-56 object-cover rounded-xl shadow-md border border-gray-100">
                        <p class="text-[10px] sm:text-xs text-gray-600 mt-2 text-center line-clamp-2 leading-snug">{{ $buku->judul }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes buku-scroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-buku-scroll {
            animation: buku-scroll 30s linear infinite;
        }
        .animate-buku-scroll:hover {
            animation-play-state: paused;
        }
    </style>
    @endif

    {{-- VISI MISI --}}
    <div id="visi-misi" class="bg-sky-50 py-8 sm:py-16 px-4">
    <div class="max-w-4xl mx-auto">
            <h2 class="text-lg sm:text-2xl font-bold text-center text-gray-800 mb-5 sm:mb-10">Visi & Misi SMK Negeri 1 Bojongpicung</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-8">
                <div class="bg-white rounded-2xl p-4 sm:p-7 shadow-sm border border-gray-100">
    <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5 sm:mb-4">
        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4.5 h-4.5 sm:w-5.5 sm:h-5.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-sm sm:text-lg">Visi</h3>
    </div>
    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed text-justify">
        "Menjadi Sekolah yang HAPPY (Humanis, Adaptif, Produktif, Progresif, dan Youthful) untuk menyiapkan lulusan yang religius, unggul dan berkarakter Panca Waluya sehingga siap berkarya dan berdaya saing global di era digital".
    </p>
</div>
<div class="bg-white rounded-2xl p-4 sm:p-7 shadow-sm border border-gray-100">
    <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5 sm:mb-4">
        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4.5 h-4.5 sm:w-5.5 sm:h-5.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-sm sm:text-lg">Misi</h3>
    </div>
    <ul class="text-gray-600 text-xs sm:text-sm leading-relaxed space-y-2 sm:space-y-2.5">
        <li class="flex items-start gap-2">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-justify">Membangun budaya sekolah yang ramah, beretika, religius, menghargai keberagaman, serta menumbuhkan sikap tanggung jawab sosial dan cinta tanah air sebagai wujud karakter cageur, bageur, bener, singer, dan pinter.</span>
        </li>
        <li class="flex items-start gap-2">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-justify">Mengintegrasikan literasi digital, AI, coding, data literacy, serta pembelajaran berbasis masalah agar siswa mampu berpikir analitis, memecahkan persoalan nyata, dan mengelola proses belajar.</span>
        </li>
        <li class="flex items-start gap-2">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-justify">Membangun komunitas belajar yang saling mengapresiasi, terbuka pada inovasi, mampu bekerja sama secara efektif, serta komunikatif dalam menyampaikan gagasan dan solusi.</span>
        </li>
        <li class="flex items-start gap-2">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-justify">Mengembangkan kegiatan kepemudaan, olahraga, seni, literasi, organisasi, dan pembiasaan positif yang membangun kecerdasan emosional, kesehatan mental, nalar kritis, dan kepercayaan diri generasi muda.</span>
        </li>
    </ul>
</div>
            </div>
        </div>
    </div>
    
    {{-- FOOTER --}}
    {{-- <footer class="bg-sky-900 text-sky-200 py-8 px-4"> --}}
        {{-- QR CODE KUNJUNGAN --}}
<div class="bg-white py-8 sm:py-16 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <div class="inline-block bg-sky-50 text-sky-700 text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-1 rounded mb-3 sm:mb-4 tracking-wide">
            📱 BUKU TAMU DIGITAL
        </div>
        <h2 class="text-lg sm:text-2xl font-bold text-gray-800 mb-2">Sedang Berkunjung ke Perpustakaan?</h2>
        <p class="text-gray-500 text-xs sm:text-sm mb-5 sm:mb-8 px-2">
            Scan QR code di bawah ini untuk mencatat kunjunganmu. Tidak perlu akun, tinggal isi nama dan keperluan.
        </p>

        <div class="inline-flex flex-col items-center gap-3 sm:gap-4 bg-sky-50 border border-sky-100 rounded-2xl p-4 sm:p-8 mx-4 sm:mx-0">
            <div id="qrKunjungan" class="bg-white p-3 sm:p-4 rounded-xl shadow-sm w-[140px] h-[140px] sm:w-[200px] sm:h-[200px] flex items-center justify-center [&_img]:max-w-full [&_img]:h-auto [&_canvas]:max-w-full [&_canvas]:h-auto"></div>
            <p class="text-[10px] sm:text-xs text-gray-400">Arahkan kamera HP ke QR code ini</p>
        </div>
    </div>
</div>

    {{-- FOOTER --}}
       {{-- FOOTER --}}
    <footer class="bg-sky-900 text-sky-200 py-8 px-4">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/smk_profile.jpg') }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-sky-600">
                <div>
                    <p class="text-white font-bold text-sm">SMK Negeri 1 Bojongpicung</p>
                    <p class="text-sky-400 text-xs">Sistem Perpustakaan Digital</p>
                </div>
            </div>
                    <div class="text-center sm:text-right">
                <x-copyright :dark="true" />
            </div>    
        </div>
    </footer>
<script>
    const qrSize = window.innerWidth < 640 ? 140 : 200;
    new QRCode(document.getElementById("qrKunjungan"), {
        text: "{{ route('kunjungan.index') }}",
        width: qrSize,
        height: qrSize,
        colorDark: "#0c4a6e",
        colorLight: "#ffffff"
    });
</script>
</body>
</html>