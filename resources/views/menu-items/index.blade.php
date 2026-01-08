<x-app-layout>
    <x-slot name="title">Daftar Menu</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Daftar Menu</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola semua item menu restoran</p>
        </div>
        <x-ui.button href="{{ route('menu-items.create') }}" variant="primary">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Menu
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

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('menu-items.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input name="search" label="Cari" placeholder="Nama menu..." :value="request('search')" />
            </div>
            <div class="w-48">
                <x-ui.select name="category" label="Kategori" :value="request('category')" placeholder="Semua Kategori">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </x-ui.button>
                @if (request('search') || request('category'))
                    <x-ui.button href="{{ route('menu-items.index') }}" variant="outline">
                        Reset
                    </x-ui.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Menu Grid -->
    @if ($menuItems->isEmpty())
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-gray-800 dark:text-white/90">Belum ada menu</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan menu pertama Anda.</p>
                <x-ui.button href="{{ route('menu-items.create') }}" variant="primary" class="mt-6">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Menu
                </x-ui.button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($menuItems as $item)
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden transition-shadow hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
                    <!-- Image -->
                    <div class="aspect-video bg-gray-100 dark:bg-gray-800 relative">
                        @if ($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-5xl">{{ $item->category->icon ?? '🍽️' }}</span>
                            </div>
                        @endif

                        <!-- Status Badges -->
                        @if (!$item->is_available)
                            <div class="absolute top-2 right-2">
                                <x-ui.badge variant="error">Habis</x-ui.badge>
                            </div>
                        @endif

                        @if (!$item->is_active)
                            <div class="absolute inset-0 bg-gray-900/60 flex items-center justify-center">
                                <span class="text-white font-medium bg-gray-900/80 px-3 py-1 rounded-full text-sm">Nonaktif</span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white/90">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->category->name }}</p>
                            </div>
                        </div>

                        <p class="text-lg font-bold text-brand-600 dark:text-brand-400 mb-4">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <form action="{{ route('menu-items.toggle-availability', $item) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-center text-sm py-2 rounded-lg font-medium transition-colors {{ $item->is_available 
                                    ? 'bg-success-50 text-success-700 hover:bg-success-100 dark:bg-success-500/10 dark:text-success-400 dark:hover:bg-success-500/20' 
                                    : 'bg-warning-50 text-warning-700 hover:bg-warning-100 dark:bg-warning-500/10 dark:text-warning-400 dark:hover:bg-warning-500/20' }}">
                                    {{ $item->is_available ? '✓ Tersedia' : '✗ Tidak Tersedia' }}
                                </button>
                            </form>
                            <a href="{{ route('menu-items.edit', $item) }}"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('menu-items.destroy', $item) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-error-500 hover:bg-error-50 hover:text-error-600 dark:border-gray-700 dark:hover:bg-error-500/10">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $menuItems->links() }}
        </div>
    @endif
</x-app-layout>
