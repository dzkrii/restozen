<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('employees.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Karyawan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required autofocus>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="08123456789">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Pilih Role</option>
                                    <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Waiter</option>
                                    <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required minlength="8">
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- PIN --}}
                            <div>
                                <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">
                                    PIN (6 Digit)
                                </label>
                                <input type="text" name="pin" id="pin" value="{{ old('pin') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       pattern="[0-9]{6}" maxlength="6" placeholder="123456">
                                <p class="mt-1 text-xs text-gray-500">Untuk login cepat di kasir (opsional)</p>
                                @error('pin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Avatar --}}
                            <div class="md:col-span-2">
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Profil
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="avatar"
                                           class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div id="avatar-preview" class="hidden w-full h-full">
                                            <img src="" alt="Preview" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <div id="avatar-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                            <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                        </div>
                                        <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                                    </label>
                                </div>
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Set as default outlet --}}
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Jadikan outlet ini sebagai default untuk karyawan</span>
                                </label>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t">
                            <a href="{{ route('employees.index') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Karyawan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
