<x-app-layout>
    <x-slot name="title">Edit Kategori Menu</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('menu-categories.index') }}" 
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Kategori Menu</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi kategori</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="mx-auto max-w-2xl">
        <x-ui.card title="Informasi Kategori" description="Perbarui data kategori menu di bawah ini">
            <form action="{{ route('menu-categories.update', $menuCategory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <x-ui.input 
                        name="name" 
                        label="Nama Kategori" 
                        placeholder="Masukkan nama kategori"
                        :value="$menuCategory->name"
                        required 
                        autofocus 
                    />

                    <!-- Icon -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Icon (Emoji)
                        </label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon', $menuCategory->icon) }}"
                            placeholder="🍔"
                            class="shadow-theme-xs h-14 w-20 rounded-lg border border-gray-300 bg-transparent text-center text-3xl focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900" />
                        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">Gunakan emoji untuk icon kategori</p>
                        @error('icon')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Toggle -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <x-ui.toggle name="is_active" :checked="$menuCategory->is_active" label="Kategori aktif dan ditampilkan" />
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <x-ui.button href="{{ route('menu-categories.index') }}" variant="outline">
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
</x-app-layout>
