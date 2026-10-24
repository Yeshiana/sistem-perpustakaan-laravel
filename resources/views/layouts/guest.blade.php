<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Perpustakaan SMKN 1 Bojongpicung') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex">

            {{-- PANEL KIRI: Background foto gedung sekolah --}}
            <div class="hidden lg:block lg:w-1/2 sticky top-0 h-screen">
    <div class="relative overflow-hidden h-full">
            {{-- Background foto gedung --}}
                <img src="{{ asset('images/smkgedung.jpeg') }}"
                     class="absolute inset-0 w-full h-full object-cover"
                     alt="SMK Negeri 1 Bojongpicung">

                {{-- Overlay gradasi biru --}}
                <div class="absolute inset-0 bg-gradient-to-br from-sky-900/90 via-sky-800/75 to-sky-600/50"></div>

                {{-- Konten di atas overlay --}}
                <div class="relative z-10 flex flex-col justify-between h-full p-10">

                    {{-- Logo + Nama Sekolah atas --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.jpeg') }}"
                             class="w-12 h-12 rounded-xl object-cover border-2 border-white/40 shadow-lg">
                        <div>
                            <p class="text-white font-bold text-sm leading-tight">SMK Negeri 1 Bojongpicung</p>
                            <p class="text-sky-200 text-xs">Sistem Perpustakaan Digital</p>
                        </div>
                    </div>

                    {{-- Teks tengah --}}
                    <div>
                        <div class="inline-flex items-center gap-2 bg-yellow-400/90 text-gray-900 text-xs font-bold px-3 py-1.5 rounded-full mb-5">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292z"/>
                            </svg>
                            Perpustakaan Digital
                        </div>
                        <h1 class="text-4xl font-bold text-white leading-tight mb-4 drop-shadow-lg">
                            Selamat Datang di<br>
                            <span class="text-yellow-300">Perpustakaan<br>SMKN 1 Bojongpicung</span>
                        </h1>
                        <p class="text-sky-100 text-sm leading-relaxed max-w-sm">
                            Akses ribuan koleksi buku, kelola peminjaman, dan nikmati layanan perpustakaan digital yang mudah dan modern.
                        </p>
                    </div>

                    {{-- Stats bawah --}}
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20 text-center">
                            <p class="text-xl font-bold text-white">500+</p>
                            <p class="text-sky-200 text-xs mt-0.5">Koleksi Buku</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20 text-center">
                            <p class="text-xl font-bold text-white">200+</p>
                            <p class="text-sky-200 text-xs mt-0.5">Anggota Aktif</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20 text-center">
                            <p class="text-xl font-bold text-white">24/7</p>
                            <p class="text-sky-200 text-xs mt-0.5">Akses Digital</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>

                        {{-- PANEL KANAN: Form Login --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-start lg:justify-center items-center bg-gradient-to-br from-sky-50 via-white to-sky-100 px-6 py-8 lg:py-12">
                                {{-- Tombol Kembali ke Beranda --}}
                <div class="w-full max-w-md mb-3">
                    <a href="{{ route('welcome') }}"
                       title="Kembali ke Beranda"
                       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white border border-sky-100 text-sky-600 hover:bg-sky-50 hover:text-sky-800 shadow-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                </div>
                {{-- Logo mobile (hanya muncul di layar kecil) --}}
                <div class="lg:hidden flex flex-col items-center mb-5">
            <img src="{{ asset('images/logo.jpeg') }}"
                         class="w-16 h-16 rounded-2xl object-cover border-2 border-sky-200 shadow-lg mb-3">
                    <p class="text-sky-900 font-bold text-sm">SMK Negeri 1 Bojongpicung</p>
                    <p class="text-sky-500 text-xs">Sistem Perpustakaan Digital</p>
                </div>

                {{-- Card Form --}}
                <div class="w-full max-w-md">

                    {{-- Header logo desktop --}}
                    <div class="hidden lg:flex items-center gap-3 mb-8">
                        <img src="{{ asset('images/logo.jpeg') }}"
                             class="w-11 h-11 rounded-xl object-cover border-2 border-sky-200 shadow">
                        <div>
                            <p class="text-sky-900 font-bold text-sm leading-tight">SMK Negeri 1 Bojongpicung</p>
                            <p class="text-sky-400 text-xs">Sistem Perpustakaan Digital</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Masuk ke Sistem</h2>
                            <p class="text-gray-400 text-sm mt-1">Silakan masukkan NISN dan password kamu</p>
                        </div>
                        {{ $slot }}
                    </div>

                          <div class="mt-6">
                        <x-copyright />
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>