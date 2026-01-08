<x-app-layout>
    <x-slot name="title">Tambah Karyawan</x-slot>

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
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Karyawan</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftarkan karyawan baru</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-3xl">
        <x-ui.card title="Informasi Karyawan" description="Lengkapi data karyawan di bawah ini">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <x-ui.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" required autofocus />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-ui.input type="email" name="email" label="Email" placeholder="email@example.com" required />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-ui.input name="phone" label="Nomor Telepon" placeholder="08123456789" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-ui.select name="role" label="Role" required placeholder="Pilih Role">
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Waiter</option>
                            <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                        </x-ui.select>
                    </div>

                    <!-- Password -->
                    <div>
                        <x-ui.input type="password" name="password" label="Password" hint="Minimal 8 karakter" required />
                    </div>

                    <!-- PIN -->
                    <div class="md:col-span-2">
                        <x-ui.input name="pin" label="PIN (6 Digit)" placeholder="123456" hint="Untuk login cepat di kasir (opsional)" />
                    </div>

                    <!-- Avatar -->
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Foto Profil
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="avatar"
                                class="flex flex-col items-center justify-center w-full h-40 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800">
                                <div id="avatar-preview" class="hidden w-full h-full">
                                    <img src="" alt="Preview" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div id="avatar-placeholder" class="flex flex-col items-center justify-center py-6">
                                    <svg class="size-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Klik untuk upload</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG (Max. 2MB)</p>
                                </div>
                                <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                            </label>
                        </div>
                        @error('avatar')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Default Outlet -->
                    <div class="md:col-span-2">
                        <x-ui.toggle name="is_default" :checked="old('is_default')" label="Jadikan outlet ini sebagai default untuk karyawan" />
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
                        Simpan Karyawan
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
    </script>
</x-app-layout>
