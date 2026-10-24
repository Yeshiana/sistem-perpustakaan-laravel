<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Detail Anggota
        </h2>
    </x-slot>
    <div class="py-4 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6">
                <div class="flex items-center gap-3 sm:gap-6 mb-4 sm:mb-6">
                    @if($anggota->foto)
                        <img src="{{ asset('storage/'.$anggota->foto) }}"
                             class="h-16 w-16 sm:h-24 sm:w-24 rounded-full object-cover border shrink-0">
                    @else
                        <div class="h-16 w-16 sm:h-24 sm:w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-lg sm:text-2xl shrink-0">
                            👤
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-base sm:text-xl font-bold truncate">{{ $anggota->name }}</h3>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            <span class="px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold
                                {{ $anggota->role == 'siswa' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($anggota->role) }}
                            </span>
                            <span class="px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold
                                {{ $anggota->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $anggota->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border">
                <table class="w-full text-xs sm:text-sm">
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium w-28 sm:w-40">Kode Anggota</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-mono">{{ $anggota->kode_anggota ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Nama Lengkap</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->name }}</td>
                    </tr>
                    @if($anggota->role == 'siswa')
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">NISN</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Email</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b break-all">{{ $anggota->email ?? '-' }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">No. HP</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Jurusan</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->jurusan ?? '-' }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Kelas</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Tahun Masuk</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->tahun_masuk ?? '-' }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Aktif Hingga</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->tanggal_aktif_hingga ?? '-' }}</td>
                    </tr>
                    @else
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Email</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b break-all">{{ $anggota->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Jabatan</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->jabatan ?? '-' }}</td>
                    </tr>
                    @endif
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Tanggal Lahir</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->ttl ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium align-top">Alamat</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->alamat ?? '-' }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b font-medium">Status Akun</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 border-b">{{ $anggota->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    </tr>
                    <tr>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5 font-medium">Terdaftar Sejak</td>
                        <td class="px-2.5 sm:px-4 py-2 sm:py-2.5">{{ $anggota->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-2 mt-4 sm:mt-6">
                    <a href="{{ route('pengurus.anggota.index') }}"
                       class="bg-gray-300 text-gray-700 text-center text-sm px-4 py-2.5 sm:py-2 rounded-lg hover:bg-gray-400">
                        ← Kembali
                    </a>
                    <a href="{{ route('pengurus.anggota.edit', $anggota) }}"
                       class="bg-blue-600 text-white text-center text-sm px-4 py-2.5 sm:py-2 rounded-lg hover:bg-blue-700">
                        Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>