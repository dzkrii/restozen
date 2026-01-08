<x-app-layout>
    <x-slot name="title">Tambah Menu</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('menu-items.index') }}" 
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Menu</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat item menu baru</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-3xl">
        <x-ui.card title="Informasi Menu" description="Lengkapi data menu di bawah ini">
            <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <x-ui.input name="name" label="Nama Menu" placeholder="Masukkan nama menu" required autofocus />
                    </div>

                    <!-- Category -->
                    <div>
                        <x-ui.select name="menu_category_id" label="Kategori" required placeholder="Pilih Kategori">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('menu_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <!-- SKU -->
                    <div>
                        <x-ui.input name="sku" label="SKU (Kode)" placeholder="MNU-001" />
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Harga Jual <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 text-sm dark:text-gray-400">Rp</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                min="0" step="100" required>
                        </div>
                        @error('price')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cost Price -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Harga Modal (HPP)
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 text-sm dark:text-gray-400">Rp</span>
                            </div>
                            <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}"
                                class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                min="0" step="100">
                        </div>
                        @error('cost_price')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-ui.textarea name="description" label="Deskripsi" placeholder="Deskripsi singkat menu..." :rows="3" />
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Gambar Menu
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-40 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800">
                                <div id="image-preview" class="hidden w-full h-full">
                                    <img src="" alt="Preview" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div id="image-placeholder" class="flex flex-col items-center justify-center py-6">
                                    <svg class="size-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Klik untuk upload</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, WEBP (Max. 2MB)</p>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Management -->
                    <div class="md:col-span-2 rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="font-medium text-gray-800 dark:text-white/90 mb-4">Manajemen Stok</h3>

                        <div class="mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="track_stock" id="track_stock" value="1"
                                    {{ old('track_stock') ? 'checked' : '' }}
                                    class="sr-only peer"
                                    onchange="toggleStockInput()">
                                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-brand-500 peer-focus:ring-4 peer-focus:ring-brand-500/20 dark:bg-gray-700 transition-colors">
                                    <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-md transition-transform peer-checked:translate-x-5"></div>
                                </div>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-400">Lacak stok menu ini</span>
                            </label>
                        </div>

                        <div id="stock-input" class="{{ old('track_stock') ? '' : 'hidden' }}">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jumlah Stok
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}"
                                class="shadow-theme-xs h-11 w-32 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white/90"
                                min="0">
                            @error('stock')
                                <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2 flex flex-wrap gap-6">
                        <x-ui.toggle name="is_available" :checked="true" label="Tersedia untuk dipesan" />
                        <x-ui.toggle name="is_active" :checked="true" label="Menu aktif" />
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <x-ui.button href="{{ route('menu-items.index') }}" variant="outline">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Menu
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');

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

        function toggleStockInput() {
            const checkbox = document.getElementById('track_stock');
            const stockInput = document.getElementById('stock-input');

            if (checkbox.checked) {
                stockInput.classList.remove('hidden');
            } else {
                stockInput.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
