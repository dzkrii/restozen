<x-app-layout>
    <x-slot name="title">Tambah Meja</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('tables.index') }}" 
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Meja</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat meja baru untuk restoran</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-2xl">
        <x-ui.card title="Informasi Meja" description="Lengkapi data meja di bawah ini">
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Area -->
                    <x-ui.select name="table_area_id" label="Area Meja" :value="old('table_area_id', request('area'))" placeholder="Pilih Area Meja" :required="true">
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ old('table_area_id', request('area')) == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <!-- Table Number -->
                    <x-ui.input 
                        name="number" 
                        label="Nomor Meja" 
                        placeholder="Contoh: T01, A1, VIP-1"
                        hint="Nomor ini akan ditampilkan di QR code"
                        required 
                        autofocus 
                    />

                    <!-- Table Name -->
                    <x-ui.input 
                        name="name" 
                        label="Nama Meja (Opsional)" 
                        placeholder="Contoh: Meja Dekat Jendela, VIP Room 1"
                    />

                    <!-- Capacity -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kapasitas <span class="text-error-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 4) }}"
                                class="shadow-theme-xs h-11 w-24 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                min="1" max="50" required>
                            <span class="text-sm text-gray-500 dark:text-gray-400">orang</span>
                        </div>
                        @error('capacity')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <x-ui.toggle name="is_active" :checked="true" label="Meja aktif dan dapat digunakan" />

                    <!-- Info -->
                    <div class="rounded-xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-500/30 dark:bg-brand-500/10">
                        <div class="flex gap-3">
                            <svg class="size-5 flex-shrink-0 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-brand-700 dark:text-brand-300">QR code akan otomatis dibuat setelah meja ditambahkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <x-ui.button href="{{ route('tables.index') }}" variant="outline">
                        Batal
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Meja
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
