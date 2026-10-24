<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Anggota
        </h2>
    </x-slot>
    <div class="py-3 sm:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
                <form action="{{ route('pengurus.anggota.update',$anggota->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $anggota->name) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($anggota->role == 'siswa')
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" value="{{ $anggota->nisn }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100" disabled>
                        <p class="text-xs text-gray-400 mt-1">NISN tidak dapat diubah</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div class="col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan', $anggota->jurusan) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                            @error('jurusan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $anggota->kelas) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                            @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">No. HP</label>
    <input type="text" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
    @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>
                    @else
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Email</label>
                        <input type="text" value="{{ $anggota->email }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100" disabled>
                        <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $anggota->jabatan) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <!-- TTL -->
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="ttl" value="{{ old('ttl', $anggota->ttl) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        @error('ttl') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">{{ old('alamat', $anggota->alamat) }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Foto Profil</label>
                        <div class="mt-1 flex items-center gap-3">
                            @if($anggota->foto)
                                <img src="{{ asset('storage/'.$anggota->foto) }}"
                                     class="h-12 w-12 rounded-full object-cover shrink-0">
                            @endif
                            <input type="file" name="foto" accept="image/*"
                                   class="block w-full min-w-0 text-xs text-gray-500
                                          file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                          file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700
                                          hover:file:bg-gray-200">
                        </div>
                        @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <!-- Reset Password -->
                    <div class="border-t border-gray-200 pt-3 mt-4 mb-3">
                        <p class="text-xs sm:text-sm font-semibold text-gray-700">Reset Password</p>
                        <p class="text-xs text-gray-400 mb-3">Kosongkan jika tidak ingin mengubah password.</p>

                        <div class="mb-3">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="passwordBaru" autocomplete="new-password"
                                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 pr-10 text-sm">
                                <button type="button" onclick="togglePasswordAdmin('passwordBaru', this)"
                                        class="absolute inset-y-0 right-0 mt-1 px-3 flex items-center text-gray-500 hover:text-gray-700">
                                    <svg class="eyeOpen w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <svg class="eyeClosed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">Ulangi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="passwordUlangi" autocomplete="new-password"
                                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 pr-10 text-sm">
                                <button type="button" onclick="togglePasswordAdmin('passwordUlangi', this)"
                                        class="absolute inset-y-0 right-0 mt-1 px-3 flex items-center text-gray-500 hover:text-gray-700">
                                    <svg class="eyeOpen w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <svg class="eyeClosed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <a href="{{ route('pengurus.anggota.index') }}"
                           class="flex-1 sm:flex-none text-center bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                               class="flex-1 sm:flex-none bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordAdmin(fieldId, btn) {
            const field = btn.closest('.relative').querySelector('#' + fieldId);
            const eyeOpen = btn.querySelector('.eyeOpen');
            const eyeClosed = btn.querySelector('.eyeClosed');

            if (field.type === 'password') {
                field.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                field.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>