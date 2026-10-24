<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Masuk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-base">Semua Laporan dari Pengurus</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Periksa dan berikan keputusan untuk setiap laporan</p>
                </div>

                @if($laporan->isEmpty())
                    <div class="py-16 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                        </svg>
                        <p class="text-gray-400 text-sm">Belum ada laporan masuk</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($laporan as $lap)
                        <div class="px-6 py-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                        {{ $lap->status == 'menunggu' ? 'bg-yellow-50' : ($lap->status == 'disetujui' ? 'bg-green-50' : 'bg-red-50') }}">
                                        <svg class="w-5 h-5 {{ $lap->status == 'menunggu' ? 'text-yellow-500' : ($lap->status == 'disetujui' ? 'text-green-500' : 'text-red-500') }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $lap->judul }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Dari: <span class="font-medium text-gray-600">{{ $lap->pengurus->name ?? '-' }}</span>
                                            · {{ $lap->created_at->diffForHumans() }}
                                        </p>
                                        @if($lap->keterangan)
                                            <p class="text-xs text-gray-500 mt-1 bg-gray-50 rounded-lg px-3 py-1.5">{{ $lap->keterangan }}</p>
                                        @endif
                                        @if($lap->status == 'ditolak' && $lap->koreksi)
                                            <p class="text-xs text-red-600 mt-1 bg-red-50 rounded-lg px-3 py-1.5">
                                                <span class="font-semibold">Catatan koreksi:</span> {{ $lap->koreksi }}
                                            </p>
                                        @endif
                                        <a href="{{ asset('storage/'.$lap->file_laporan) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs text-sky-600 hover:underline mt-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                            Unduh / Lihat File
                                        </a>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                                        {{ $lap->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' :
                                           ($lap->status == 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($lap->status) }}
                                    </span>

                                    @if($lap->status == 'menunggu')
                                    <div class="flex gap-2">
                                        {{-- Tombol ACC --}}
                                        <form action="{{ route('kepala.laporan.acc', $lap->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('ACC laporan ini?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium">
                                                ✓ ACC
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak --}}
                                        <button onclick="document.getElementById('tolak-{{ $lap->id }}').classList.toggle('hidden')"
                                                class="bg-red-100 hover:bg-red-200 text-red-600 text-xs px-3 py-1.5 rounded-lg transition font-medium">
                                            ✕ Tolak
                                        </button>
                                    </div>

                                    {{-- Form koreksi --}}
                                    <div id="tolak-{{ $lap->id }}" class="hidden w-64">
                                        <form action="{{ route('kepala.laporan.tolak', $lap->id) }}" method="POST" class="space-y-2">
                                            @csrf @method('PATCH')
                                            <textarea name="koreksi" rows="2" required
                                                      placeholder="Tuliskan catatan koreksi..."
                                                      class="w-full border border-red-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-red-300 outline-none resize-none"></textarea>
                                            <button type="submit"
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white text-xs py-1.5 rounded-lg transition font-medium">
                                                Kirim Penolakan
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>