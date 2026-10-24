<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">🎓 Daftar sebagai Siswa</h2>
    </div>

    <form method="POST" action="{{ route('register.siswa.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- ========== DATA SISWA ========== -->
        <div class="border-l-4 border-blue-400 pl-4 mb-4">
            <p class="text-sm font-semibold text-blue-600 mb-2">▸ Data Siswa</p>

            <!-- NISN -->
            <div class="mb-4">
                <x-input-label for="nisn" value="NISN" />
                <x-text-input id="nisn" type="text" name="nisn" maxlength="10"
                    :value="old('nisn')" class="block mt-1 w-full" required
                    placeholder="10 digit NISN" />
                <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
            </div>

            <!-- No HP -->
            <div class="mb-4">
                <x-input-label for="no_hp" value="No HP (WhatsApp)" />
                <x-text-input id="no_hp" type="text" name="no_hp"
                    :value="old('no_hp')" class="block mt-1 w-full" required
                    placeholder="Contoh: 08123456789" />
                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
            </div>

            <!-- Jurusan & Kelas -->
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan" class="mt-1 block w-full border rounded px-3 py-2" required>
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
                    <select name="kelas" class="mt-1 block w-full border rounded px-3 py-2" required>
                        <option value="">Pilih Kelas</option>
                        <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                    </select>
                    @error('kelas') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tahun Masuk -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tahun Masuk <span class="text-red-500">*</span></label>
                <select name="tahun_masuk" class="mt-1 block w-full border rounded px-3 py-2" required>
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

        <!-- ========== DATA DIRI ========== -->
        <div class="border-t border-gray-200 pt-4 mt-4">
            <p class="text-sm font-semibold text-gray-600 mb-2">▸ Data Diri</p>

            <!-- Nama -->
            <div class="mb-4">
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" type="text" name="name"
                    :value="old('name')" class="block mt-1 w-full" required
                    placeholder="Masukkan nama lengkap" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email"
                    :value="old('email')" class="block mt-1 w-full" required
                    placeholder="Contoh: nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

           <!-- Password & Konfirmasi -->
<div class="grid grid-cols-2 gap-4">
    <div class="mb-4">
        <x-input-label for="password" value="Password (min. 8 karakter)" />
        <div class="relative">
            <x-text-input id="password" type="password" name="password"
                class="block mt-1 w-full pr-10" required />
            <button type="button" onclick="togglePassword('password', this)"
    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 014.02-5.197M9.879 9.879A3 3 0 0012 15a3 3 0 002.121-5.121M3 3l18 18"/>
    </svg>
</button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
        <div class="relative">
            <x-text-input id="password_confirmation" type="password"
                name="password_confirmation" class="block mt-1 w-full pr-10" required />
            <button type="button" onclick="togglePassword('password_confirmation', this)"
    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 014.02-5.197M9.879 9.879A3 3 0 0012 15a3 3 0 002.121-5.121M3 3l18 18"/>
    </svg>
</button>
        </div>
    </div>
</div>

            <!-- TTL -->
            <div class="mb-4">
                <x-input-label for="ttl" value="Tanggal Lahir" />
                <x-text-input id="ttl" type="date" name="ttl"
                    :value="old('ttl')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('ttl')" class="mt-2" />
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <x-input-label for="alamat" value="Alamat" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                    required>{{ old('alamat') }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <!-- Foto -->
            <!-- Foto -->
<div class="mb-4">
    <x-input-label for="foto" value="Pas Foto" />
    <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
        class="block mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
    <p class="text-xs text-gray-400 mt-1">Maksimal 2MB, format JPG/JPEG/PNG.</p>
    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
</div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali ke Login
            </a>
            <x-primary-button>Daftar</x-primary-button>
        </div>
    </form>

    <script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
    const isNowHidden = field.type === 'password';
    btn.querySelector('svg').innerHTML = isNowHidden
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 014.02-5.197M9.879 9.879A3 3 0 0012 15a3 3 0 002.121-5.121M3 3l18 18"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>
</x-guest-layout>