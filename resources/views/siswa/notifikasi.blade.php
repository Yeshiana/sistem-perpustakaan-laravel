<x-app-layout>
        <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Notifikasi
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

                        <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6">
                <div class="flex justify-between items-center mb-3 sm:mb-4">
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Semua Notifikasi</h3>
                    @if($notifikasi->count() > 0)
                        <form action="{{ route('siswa.notifikasi.destroyAll') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Hapus semua notifikasi?')"
                                class="text-[10px] sm:text-xs text-red-500 hover:underline">
                                Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>

                {{-- FILTER SELECT --}}
                <form method="GET" action="{{ route('siswa.notifikasi') }}" class="mb-3 sm:mb-4">
                    <select name="kategori" onchange="this.form.submit()"
                            class="w-full sm:w-auto border border-gray-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">📬 Semua Notifikasi</option>
                        <option value="peminjaman" {{ request('kategori') == 'peminjaman' ? 'selected' : '' }}>📋 Peminjaman</option>
                        <option value="pengembalian" {{ request('kategori') == 'pengembalian' ? 'selected' : '' }}>📦 Pengembalian</option>
                        <option value="sanksi" {{ request('kategori') == 'sanksi' ? 'selected' : '' }}>⚠️ Sanksi</option>
                    </select>
                </form>

                               @forelse($notifikasi as $item)
                    <div class="flex items-start gap-2 sm:gap-3 p-2.5 sm:p-4 rounded-lg mb-2 {{ $item->is_read ? 'bg-gray-50' : 'bg-blue-50 border border-blue-100' }}">
                        <div class="text-lg sm:text-2xl mt-0.5 sm:mt-1 shrink-0">
                            @if($item->kategori == 'peminjaman') 📋
                            @elseif($item->kategori == 'pengembalian') 📦
                            @elseif($item->kategori == 'sanksi') ⚠️
                            @else 🔔
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-700">{{ preg_replace('/\s*\[[A-Z0-9]+\]/', '', $item->pesan) }}</p>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                        <form action="{{ route('siswa.notifikasi.destroy', $item) }}" method="POST" class="shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-400 text-xs">✕</button>
                        </form>
                    </div>
                @empty
                
                <div class="text-center py-12">
                        <div class="text-5xl mb-3">🔔</div>
                        <p class="text-gray-400 text-sm">Belum ada notifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>