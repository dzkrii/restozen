<x-app-layout>
    <x-slot name="title">Edit Meja - {{ $table->number }}</x-slot>

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
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Meja</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Meja {{ $table->number }}</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-2xl">
        <x-ui.card title="Informasi Meja" description="Perbarui data meja di bawah ini">
            <form id="edit-form" action="{{ route('tables.update', $table) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Area -->
                    <x-ui.select name="table_area_id" label="Area Meja" :value="$table->table_area_id" placeholder="Pilih Area Meja" :required="true">
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ old('table_area_id', $table->table_area_id) == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <!-- Table Number -->
                    <x-ui.input 
                        name="number" 
                        label="Nomor Meja" 
                        placeholder="Contoh: T01, A1, VIP-1"
                        :value="$table->number"
                        required 
                        autofocus 
                    />

                    <!-- Table Name -->
                    <x-ui.input 
                        name="name" 
                        label="Nama Meja (Opsional)" 
                        placeholder="Contoh: Meja Dekat Jendela, VIP Room 1"
                        :value="$table->name"
                    />

                    <!-- Capacity -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kapasitas <span class="text-error-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $table->capacity) }}"
                                class="shadow-theme-xs h-11 w-24 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                min="1" max="50" required>
                            <span class="text-sm text-gray-500 dark:text-gray-400">orang</span>
                        </div>
                        @error('capacity')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- QR Code -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                        <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-400">QR Code URL</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 break-all mb-4">{{ $table->qr_code }}</p>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.button href="{{ route('tables.download-qr', $table) }}" variant="secondary" size="sm">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download QR
                            </x-ui.button>
                            <x-ui.button type="submit" form="regenerate-qr-form" variant="outline" size="sm">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Regenerate
                            </x-ui.button>
                        </div>
                    </div>

                <!-- Status -->
                    <x-ui.toggle name="is_active" :checked="$table->is_active" label="Meja aktif dan dapat digunakan" />
                </div>
            </form>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-800">
                    <form action="{{ route('tables.destroy', $table) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus meja ini?')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="ghost" class="text-error-600 hover:bg-error-50 dark:text-error-400 dark:hover:bg-error-500/10">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Meja
                        </x-ui.button>
                    </form>

                    <form id="regenerate-qr-form" action="{{ route('tables.regenerate-qr', $table) }}" method="POST">
                        @csrf
                    </form>

                    <div class="flex items-center gap-3">
                        <x-ui.button href="{{ route('tables.index') }}" variant="outline">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit" form="edit-form" variant="primary">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </x-ui.button>
                    </div>
                </div>
        </x-ui.card>
    </div>
</x-app-layout>
