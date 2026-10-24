<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Tambah Anggota
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6">
                <form action="{{ route('pengurus.anggota.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- ... (kode role, siswa fields, guru fields tetap sama) ... -->

                    <!-- ========== ROLE ========== -->
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
    <select name="role" id="role"
            class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
            required>
        <option value="">Pilih Role</option>
        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
    </select>
    @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
</div>

<!-- ========== FIELD KHUSUS SISWA ========== -->
<div id="siswa-fields" class="hidden border-l-4 border-blue-400 pl-4 mb-4">
    <p class="text-sm font-semibold text-blue-600 mb-2">▸ Data Siswa</p>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">NISN <span class="text-red-500">*</span></label>
        <input type="text" name="nisn" value="{{ old('nisn') }}"
               class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
               placeholder="10 digit NISN" maxlength="10">
        @error('nisn') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">No HP <span class="text-red-500">*</span></label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
               class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
               placeholder="Contoh: 08123456789">
        @error('no_hp') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Jurusan <span class="text-red-500">*</span></label>
            <select name="jurusan" class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Pilih Jurusan</option>
                <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                <option value="TO" {{ old('jurusan') == 'TO' ? 'selected' : '' }}>TO</option>
                <option value="AKL" {{ old('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
                <option value="AT" {{ old('jurusan') == 'AT' ? 'selected' : '' }}>AT</option>
                <option value="APHP" {{ old('jurusan') == 'APHP' ? 'selected' : '' }}>APHP</option>
                <option value="APAT" {{ old('jurusan') == 'APAT' ? 'selected' : '' }}>APAT</option>
            </select>
            @error('jurusan') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Kelas <span class="text-red-500">*</span></label>
            <select name="kelas" class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Pilih Kelas</option>
                <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
                <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
            </select>
            @error('kelas') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Tahun Masuk <span class="text-red-500">*</span></label>
        <select name="tahun_masuk" class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Pilih Tahun</option>
            @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                <option value="{{ $year }}" {{ old('tahun_masuk') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endfor
        </select>
        @error('tahun_masuk') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>
</div>



<!-- ========== FIELD KHUSUS GURU ========== -->
<div id="guru-fields" class="hidden border-l-4 border-green-400 pl-4 mb-4">
    <p class="text-sm font-semibold text-green-600 mb-2">▸ Data Guru</p>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
        <input type="text" name="username" value="{{ old('username') }}"
               class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
               placeholder="Contoh: ahmad.suryadi">
        @error('username') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        <p class="text-xs text-gray-400 mt-1">Digunakan untuk login. Harus unik.</p>
    </div>

    <!-- ========== TAMBAHKAN FIELD JABATAN DI SINI! ========== -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Jabatan <span class="text-red-500">*</span></label>
        <input type="text" name="jabatan" value="{{ old('jabatan') }}"
               class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
               placeholder="Contoh: Guru Matematika / Guru BK / Kepala Sekolah">
        @error('jabatan') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        <p class="text-xs text-gray-400 mt-1">Jabatan/Mata Pelajaran yang diampu</p>
    </div>
</div>



                    <!-- ========== FIELD UMUM (UNTUK SEMUA ROLE) ========== -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <p class="text-sm font-semibold text-gray-600 mb-2">▸ Data Diri</p>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
                                   placeholder="Masukkan nama lengkap">
                            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm"
                                   placeholder="Contoh: nama@email.com">
                            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <!-- ========== PASSWORD DENGAN ICON MATA ========== -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
    <div class="relative">
        <input type="password" name="password" id="password"
        class="mt-1 block w-full border rounded-lg px-3 py-2 pr-10 text-sm"       
        placeholder="Minimal 8 karakter">
        <button type="button" onclick="togglePasswordEmoji('password', this)"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-lg">
            🙈
        </button>
    </div>
    @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
</div>

                            <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
    <div class="relative">
        <input type="password" name="password_confirmation" id="password_confirmation"
        class="mt-1 block w-full border rounded-lg px-3 py-2 pr-10 text-sm"       
        placeholder="Ulangi password">
        <button type="button" onclick="togglePasswordEmoji('password_confirmation', this)"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-lg">
            🙈
        </button>
    </div>
</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="ttl" value="{{ old('ttl') }}"
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">
                            @error('ttl') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" rows="3"
                                      class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">{{ old('alamat') }}</textarea>
                            @error('alamat') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                            <input type="file" name="foto" accept="image/*"
                                   class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm">
                            @error('foto') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- ========== TOMBOL ========== -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                        <a href="{{ route('pengurus.anggota.index') }}"
                           class="bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-400 text-sm text-center">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== JAVASCRIPT TOGGLE ROLE ========== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const siswaFields = document.getElementById('siswa-fields');
            const guruFields = document.getElementById('guru-fields');

            function toggleFields() {
                const role = roleSelect.value;
                if (role === 'siswa') {
                    siswaFields.classList.remove('hidden');
                    guruFields.classList.add('hidden');
                } else if (role === 'guru') {
                    siswaFields.classList.add('hidden');
                    guruFields.classList.remove('hidden');
                } else {
                    siswaFields.classList.add('hidden');
                    guruFields.classList.add('hidden');
                }
            }

            roleSelect.addEventListener('change', toggleFields);
            toggleFields();
        });

        // ========== TOGGLE PASSWORD ==========
        // ========== TOGGLE PASSWORD (emoji monyet) ==========
function togglePasswordEmoji(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = '🐵'; // password terlihat → monyet mata terbuka
    } else {
        field.type = 'password';
        btn.textContent = '🙈'; // password disembunyikan → monyet tutup mata
    }
}
    </script>
</x-app-layout>