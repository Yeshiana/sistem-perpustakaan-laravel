<x-app-layout>
       <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">Profil Saya</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            {{-- KARTU PROFIL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header biru --}}
                    
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 h-20 sm:h-32 relative">
                    <div class="absolute -bottom-10 sm:-bottom-16 left-1/2 -translate-x-1/2">
                        @if($user->foto)
                            <button type="button" onclick="document.getElementById('modalFotoProfil').classList.remove('hidden')"
                                    class="block w-20 h-26 sm:w-28 sm:h-36 rounded-2xl shadow-lg border-4 border-white bg-white overflow-hidden">
                                <img src="{{ asset('storage/'.$user->foto) }}"
                                     class="w-full h-full object-contain">
                            </button>
                        @else
                            <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl bg-white shadow-lg border-4 border-white
                                        flex items-center justify-center
                                        bg-gradient-to-br from-sky-400 to-indigo-600
                                        text-white text-xl sm:text-3xl font-bold">
                                {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info nama & role --}}
                <div class="pt-12 sm:pt-18 pb-4 sm:pb-6 px-4 sm:px-8 text-center">
                    <h3 class="text-base sm:text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <span class="inline-block mt-1 px-2.5 sm:px-3 py-0.5 bg-blue-50 text-blue-600 text-[10px] sm:text-xs font-semibold rounded-full capitalize">
                        {{ $user->role }}
                    </span>
                </div>       
            </div>

            {{-- DETAIL INFO --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h4 class="text-xs sm:text-sm font-bold text-gray-500 uppercase tracking-wide mb-2 sm:mb-4">Informasi Akun</h4>
                <div class="space-y-1 sm:space-y-4">

                    @php
                        $fields = [
                            ['label' => 'Nama Lengkap',   'value' => $user->name,      'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z'],
                            ['label' => 'Email',           'value' => $user->email,     'icon' => 'M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z'],
                            ['label' => 'NISN',            'value' => $user->nisn ?? '-','icon' => 'M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1m-4 0h4'],
                            ['label' => 'No. HP',          'value' => $user->no_hp ?? '-','icon' => 'M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                            ['label' => 'Jurusan',         'value' => $user->jurusan ?? '-','icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 0 1 .665 6.479A11.952 11.952 0 0 0 12 20.055a11.952 11.952 0 0 0-6.824-2.998a12.078 12.078 0 0 1 .665-6.479L12 14z'],
                            ['label' => 'Kelas',           'value' => $user->kelas ?? '-','icon' => 'M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4'],
                            ['label' => 'Tahun Masuk',     'value' => $user->tahun_masuk ?? '-','icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z'],
                            ['label' => 'Alamat',          'value' => $user->alamat ?? '-','icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'],
                            ['label' => 'Status Akun',     'value' => $user->is_active ? 'Aktif' : 'Tidak Aktif', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z'],
                        ];
                    @endphp

                    @foreach($fields as $field)
                    <div class="flex items-start gap-3 sm:gap-4 py-2 sm:py-3 border-b border-gray-50 last:border-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $field['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-400 font-medium">{{ $field['label'] }}</p>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 mt-0.5">{{ $field['value'] }}</p>
                        </div>
                    </div>
                    @endforeach                   

                </div>
            </div>

            {{-- INFO EDIT --}}
<div class="bg-sky-50 rounded-2xl border border-sky-100 p-3 sm:p-5 flex items-start gap-2.5 sm:gap-3">
    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-sky-100 flex items-center justify-center shrink-0 mt-0.5">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
        </svg>
    </div>
    <div>
        <p class="text-xs sm:text-sm font-semibold text-sky-800">Ingin mengubah data profil?</p>
        <p class="text-[10px] sm:text-xs text-sky-600 mt-0.5 leading-relaxed">
            Perubahan data profil hanya dapat dilakukan melalui pengurus perpustakaan. 
            Silakan hubungi pengurus secara langsung untuk memperbarui informasi akunmu.
        </p>
    </div>
</div>
        </div>
    </div>

{{-- MODAL LIHAT FOTO PROFIL --}}
@if($user->foto)
<div id="modalFotoProfil" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60"
         onclick="document.getElementById('modalFotoProfil').classList.add('hidden')"></div>
    <div class="relative z-10 max-w-lg w-full mx-4">
        <img src="{{ asset('storage/'.$user->foto) }}"
             class="w-full rounded-2xl shadow-2xl object-contain max-h-[80vh] mx-auto">
        <button onclick="document.getElementById('modalFotoProfil').classList.add('hidden')"
                class="absolute -top-3 -right-3 bg-white text-gray-800 rounded-full w-9 h-9 flex items-center justify-center shadow hover:bg-gray-100 font-bold">
            ✕
        </button>
    </div>
</div>
@endif


</x-app-layout>