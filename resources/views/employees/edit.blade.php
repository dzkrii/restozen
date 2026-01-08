<x-app-layout>
    <x-slot name="title">Edit Karyawan - {{ $employee->name }}</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('employees.index') }}" 
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $employee->name }}</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-3xl">
        <x-ui.card title="Informasi Karyawan" description="Perbarui data karyawan di bawah ini">
            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <x-ui.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :value="$employee->name" required autofocus />
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <x-ui.input type="email" name="email_display" label="Email" :value="$employee->email" disabled hint="Email tidak dapat diubah" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-ui.input name="phone" label="Nomor Telepon" placeholder="08123456789" :value="$employee->phone" />
                    </div>

                    <!-- Role -->
                    <div class="md:col-span-2">
                        <x-ui.select name="role" label="Role" :value="$currentRole" required placeholder="Pilih Role">
                            <option value="owner" {{ old('role', $currentRole) == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="manager" {{ old('role', $currentRole) == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="cashier" {{ old('role', $currentRole) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="waiter" {{ old('role', $currentRole) == 'waiter' ? 'selected' : '' }}>Waiter</option>
                            <option value="kitchen" {{ old('role', $currentRole) == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                        </x-ui.select>
                    </div>

                    <!-- Current Avatar -->
                    @if($employee->avatar)
                        <div class="md:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Foto Profil Saat Ini</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ Storage::url($employee->avatar) }}" alt="{{ $employee->name }}"
                                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                <label class="flex items-center text-sm text-error-600 cursor-pointer hover:text-error-700 dark:text-error-400 dark:hover:text-error-300">
                                    <input type="checkbox" name="remove_avatar" value="1"
                                        class="rounded border-gray-300 text-error-600 shadow-sm focus:ring-error-500 mr-2 dark:border-gray-600 dark:bg-gray-800">
                                    Hapus foto
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- New Avatar -->
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ $employee->avatar ? 'Upload Foto Baru' : 'Foto Profil' }}
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="avatar"
                                class="flex flex-col items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800">
                                <div id="avatar-preview" class="hidden w-full h-full">
                                    <img src="" alt="Preview" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div id="avatar-placeholder" class="flex flex-col items-center justify-center py-4">
                                    <svg class="size-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Klik untuk upload</p>
                                </div>
                                <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                            </label>
                        </div>
                        @error('avatar')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Security Section -->
                    <div class="md:col-span-2 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Keamanan</h3>
                    </div>

                    <!-- Change Password -->
                    <div>
                        <x-ui.input type="password" name="password" label="Password Baru" hint="Kosongkan jika tidak ingin mengubah password" />
                    </div>

                    <!-- PIN -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            PIN (6 Digit)
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="pin" id="pin" value="{{ old('pin', $employee->pin) }}"
                                class="shadow-theme-xs h-11 flex-1 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                pattern="[0-9]{6}" maxlength="6" placeholder="123456">
                            <button type="button" onclick="generateRandomPin()"
                                class="flex items-center gap-1 px-4 py-2 text-sm font-medium bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-white transition-colors">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Generate
                            </button>
                        </div>
                        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">Untuk login cepat di kasir</p>
                        @error('pin')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <x-ui.button href="{{ route('employees.index') }}" variant="outline">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>

    <script>
        function previewAvatar(input) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function generateRandomPin() {
            const pin = Math.floor(100000 + Math.random() * 900000);
            document.getElementById('pin').value = pin;
        }
    </script>
</x-app-layout>
