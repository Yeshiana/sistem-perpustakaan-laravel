<nav x-data="{ open: false }" class="bg-white border-b border-blue-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ 
                        auth()->user()->role == 'siswa' || auth()->user()->role == 'guru' 
                            ? route('siswa.dashboard') 
                            : (auth()->user()->role == 'pengurus' 
                                ? route('pengurus.dashboard') 
                                : route('kepala.dashboard')) 
                    }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-10 w-10 rounded-lg object-cover" />
                        <span class="font-extrabold text-slate-800 text-base hidden sm:block">Perpustakaan</span>
                    </a>
                </div>
            </div>


            {{-- BAGIAN SISWA --}}
            <!-- menu digeser ke kanan, dikumpulkan dalam grup pill -->
            <div class="hidden sm:flex sm:items-center sm:ml-auto sm:mr-6">
                <div class="flex items-center gap-1 bg-blue-50 rounded-full p-1">
                    @if(auth()->user()->role == 'siswa' || auth()->user()->role == 'guru')
                        <x-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('siswa.katalog')" :active="request()->routeIs('siswa.katalog')">
                            {{ __('Katalog Buku') }}
                        </x-nav-link>
                        <x-nav-link :href="route('siswa.riwayat')" :active="request()->routeIs('siswa.riwayat')">
                            {{ __('Riwayat Peminjaman') }}
                        </x-nav-link>
                        <x-nav-link :href="route('siswa.sanksi')" :active="request()->routeIs('siswa.sanksi')">
                            {{ __('Sanksi') }}
                        </x-nav-link>
                        {{-- <x-nav-link :href="route('siswa.notifikasi')" :active="request()->routeIs('siswa.notifikasi')">
                            Notifikasi
                            @php $unread = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                            @if($unread > 0)
                                <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $unread }}</span>
                            @endif
                        </x-nav-link> --}}

                        {{-- bagian PENGURUS --}}
                    @elseif(auth()->user()->role == 'pengurus')
                        <x-nav-link :href="route('pengurus.dashboard')" :active="request()->routeIs('pengurus.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengurus.anggota.index')" :active="request()->routeIs('pengurus.anggota.*')">
                            {{ __('Anggota') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengurus.buku.index')" :active="request()->routeIs('pengurus.buku.*')">
                            {{ __('Koleksi Buku') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengurus.peminjaman.index')" :active="request()->routeIs('pengurus.peminjaman.*')">
                            {{ __('Peminjaman') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengurus.sanksi.index')" :active="request()->routeIs('pengurus.sanksi.*')">
                            {{ __('Sanksi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pengurus.laporan.index')" :active="request()->routeIs('pengurus.laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>

                        {{-- bagian KEPALA PERPUSTAKAAN --}}
@elseif(auth()->user()->role == 'kepala')
    <x-nav-link :href="route('kepala.dashboard')" :active="request()->routeIs('kepala.dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    <x-nav-link :href="route('kepala.laporan.index')" :active="request()->routeIs('kepala.laporan.*')">
        {{ __('Laporan') }}
    </x-nav-link>
    <x-nav-link :href="route('kepala.permintaan-edit-buku.index')" :active="request()->routeIs('kepala.permintaan-edit-buku.*')">
        {{ __('Persetujuan Edit Buku') }}
    </x-nav-link>
@endif
                </div>
            </div>


            {{-- LONCENG ICON --}}
            @php
    $unread = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
@endphp
<div class="flex items-center gap-2">
<a href="{{ match(auth()->user()->role) {
        'pengurus' => route('pengurus.notifikasi'),
        'kepala'   => route('kepala.notifikasi'),
        default    => route('siswa.notifikasi'),
    } }}"
   class="relative p-2 rounded-full hover:bg-blue-50 transition">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-6 h-6 text-gray-600 hover:text-blue-600"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032
              2.032 0 0118 14.158V11
              a6.002 6.002 0 00-4-5.659V4
              a2 2 0 10-4 0v1.341C7.67
              6.165 6 8.388 6 11v3.159
              c0 .538-.214 1.055-.595
              1.436L4 17h5m6 0v1
              a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>

    @if($unread > 0)
        <span
            class="absolute -top-1 -right-1
                   w-5 h-5
                   rounded-full
                   bg-red-500
                   text-white
                   text-[10px]
                   flex items-center justify-center
                   font-bold">

            {{ $unread }}

        </span>
    @endif

</a>
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:pl-5 sm:border-l sm:border-blue-100">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 group focus:outline-none">
    @if(Auth::user()->foto)
        <img src="{{ asset('storage/'.Auth::user()->foto) }}"
             class="w-9 h-9 rounded-full object-cover shrink-0 border border-blue-100">
    @else
        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
            {{ collect(explode(' ', Auth::user()->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
        </span>
    @endif
    <span class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition">
        {{ Auth::user()->name }}
    </span>
                        {{-- <button class="flex items-center gap-2 group focus:outline-none">
                            <span class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                {{ collect(explode(' ', Auth::user()->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                            </span>
                            <span class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition">
                                {{ Auth::user()->name }}
                            </span> --}}
                            <svg class="fill-current h-4 w-4 text-slate-400 group-hover:translate-y-0.5 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    
                    <x-slot name="content">
    {{-- Info user --}}
    <div class="px-4 py-3 border-b border-gray-100">
        <p class="text-xs font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
        <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
    </div>

    {{-- Lihat Profil --}}
    <x-dropdown-link :href="auth()->user()->role == 'pengurus' ? route('pengurus.profil') : route('siswa.profil')">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
            </svg>
            Lihat Profil
        </div>
    </x-dropdown-link>

    {{-- Log Out --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
            <div class="flex items-center gap-2 text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>
                </svg>
                Log Out
            </div>
        </x-dropdown-link>
    </form>
</x-slot>

                </x-dropdown>
            </div>

                        <!-- Hamburger dengan foto profil -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center focus:outline-none">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/'.Auth::user()->foto) }}"
                             class="w-9 h-9 rounded-full object-cover border-2 {{ 'border-transparent' }}"
                             :class="{ 'border-blue-500': open }">
                    @else
                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-indigo-600 text-white text-xs font-bold flex items-center justify-center border-2"
                              :class="{ 'border-blue-500': open, 'border-transparent': ! open }">
                            {{ collect(explode(' ', Auth::user()->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                        </span>
                    @endif
                </button>
            </div>
        </div>
    </div>
     </div>

    <!-- Responsive Navigation Menu -->
        <!-- Responsive Navigation Menu -->
        <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white shadow-lg border-b border-gray-200">
        <div class="flex justify-end px-4 pt-3">
            <button @click="open = false" class="p-2 rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="pt-2 pb-3 space-y-1 px-2">
            @if(auth()->user()->role == 'siswa' || auth()->user()->role == 'guru')
                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.katalog')" :active="request()->routeIs('siswa.katalog')">
                    {{ __('Katalog Buku') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.riwayat')" :active="request()->routeIs('siswa.riwayat')">
                    {{ __('Riwayat Peminjaman') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.sanksi')" :active="request()->routeIs('siswa.sanksi')">
                    {{ __('Sanksi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.notifikasi')" :active="request()->routeIs('siswa.notifikasi')">
                    Notifikasi
                    @php $unread = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                    @if($unread > 0)
                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $unread }}</span>
                    @endif
                </x-responsive-nav-link>

                {{-- MOBILE RESPONSIF ADMIN/PENGURUS --}}
            @elseif(auth()->user()->role == 'pengurus')
                <x-responsive-nav-link :href="route('pengurus.dashboard')" :active="request()->routeIs('pengurus.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengurus.anggota.index')" :active="request()->routeIs('pengurus.anggota.*')">
                    {{ __('Anggota') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengurus.buku.index')" :active="request()->routeIs('pengurus.buku.*')">
                    {{ __('Koleksi Buku') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengurus.peminjaman.index')" :active="request()->routeIs('pengurus.peminjaman.*')">
                    {{ __('Peminjaman') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengurus.sanksi.index')" :active="request()->routeIs('pengurus.sanksi.*')">
                    {{ __('Sanksi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pengurus.laporan.index')" :active="request()->routeIs('pengurus.laporan.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>

               {{-- RESPONSIF KEPALA PERPUSTAKAAN --}}
@elseif(auth()->user()->role == 'kepala')
    <x-responsive-nav-link :href="route('kepala.dashboard')" :active="request()->routeIs('kepala.dashboard')">
        {{ __('Dashboard') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('kepala.laporan.index')" :active="request()->routeIs('kepala.laporan.*')">
        {{ __('Laporan') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('kepala.permintaan-edit-buku.index')" :active="request()->routeIs('kepala.permintaan-edit-buku.*')">
        {{ __('Persetujuan Edit Buku') }}
    </x-responsive-nav-link>
@endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-100">
            <div class="space-y-1 px-2">
                <x-responsive-nav-link :href="auth()->user()->role == 'pengurus' ? route('pengurus.profil') : route('siswa.profil')">
            Lihat Profil
            </x-responsive-nav-link>
                                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="!text-red-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>
                            </svg>
                            {{ __('Log Out') }}
                        </div>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>