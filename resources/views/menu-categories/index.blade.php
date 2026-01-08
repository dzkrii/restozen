<x-app-layout>
    <x-slot name="title">Kategori Menu</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Kategori Menu</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola kategori untuk mengelompokkan menu</p>
        </div>
        <x-ui.button href="{{ route('menu-categories.create') }}" variant="primary">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </x-ui.button>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <x-ui.alert variant="success">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="error">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </x-ui.alert>
    @endif

    <!-- Table Card -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        @if ($categories->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-gray-800 dark:text-white/90">Belum ada kategori</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan kategori menu pertama Anda.</p>
                <x-ui.button href="{{ route('menu-categories.create') }}" variant="primary" class="mt-6">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kategori
                </x-ui.button>
            </div>
        @else
            <!-- Table -->
            <div class="max-w-full overflow-x-auto">
                <table class="w-full min-w-[640px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Urutan</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Kategori</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jumlah Menu</p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                            </th>
                            <th class="px-5 py-3 text-right sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border-b border-gray-100 last:border-b-0 dark:border-gray-800">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-sm font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $category->sort_order }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">{{ $category->icon ?? '📁' }}</span>
                                        <div>
                                            <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $category->name }}</p>
                                            <p class="text-gray-500 text-theme-xs dark:text-gray-400">{{ $category->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <x-ui.badge>{{ $category->menu_items_count }} menu</x-ui.badge>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    @if ($category->is_active)
                                        <x-ui.badge variant="success">Aktif</x-ui.badge>
                                    @else
                                        <x-ui.badge>Nonaktif</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button href="{{ route('menu-categories.edit', $category) }}" variant="ghost" size="sm">
                                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </x-ui.button>
                                        <form action="{{ route('menu-categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="ghost" size="sm" class="text-error-600 hover:bg-error-50 dark:text-error-400 dark:hover:bg-error-500/10">
                                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
