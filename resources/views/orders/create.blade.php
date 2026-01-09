<x-app-layout>
    <x-slot name="title">Buat Pesanan Baru</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}" 
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Buat Pesanan Baru</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih tipe pesanan dan meja</p>
            </div>
        </div>
    </div>

    <!-- Order Type Selection -->
    <div class="mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Tipe Pesanan</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.create', ['type' => 'dine_in']) }}"
                    class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'dine_in' ? 'border-brand-500 bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600 dark:text-gray-300' }}">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    <span class="font-medium">Dine In</span>
                </a>
                <a href="{{ route('orders.select-menu', ['type' => 'takeaway']) }}"
                    class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'takeaway' ? 'border-brand-500 bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600 dark:text-gray-300' }}">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="font-medium">Takeaway</span>
                </a>
                <!-- <a href="{{ route('orders.select-menu', ['type' => 'delivery']) }}"
                    class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'delivery' ? 'border-brand-500 bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600 dark:text-gray-300' }}">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    <span class="font-medium">Delivery</span>
                </a> -->
            </div>
        </div>
    </div>

    @if($orderType === 'dine_in')
        <!-- Table Selection -->
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Pilih Meja</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Klik pada meja yang tersedia untuk memilih</p>
            </div>
            
            <div class="p-6 space-y-8">
                <!-- Tables by Area -->
                @forelse($tableAreas as $area)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                            <svg class="size-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $area->name }}
                        </h4>
                        
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach($area->tables as $table)
                                @php
                                    $isAvailable = $table->status === 'available';
                                    $statusColors = [
                                        'available' => 'bg-success-50 border-success-200 hover:border-success-400 hover:bg-success-100 cursor-pointer dark:bg-success-500/10 dark:border-success-500/30 dark:hover:bg-success-500/20',
                                        'occupied' => 'bg-brand-50 border-brand-200 cursor-not-allowed opacity-75 dark:bg-brand-500/10 dark:border-brand-500/30',
                                        'reserved' => 'bg-purple-50 border-purple-200 cursor-not-allowed opacity-75 dark:bg-purple-500/10 dark:border-purple-500/30',
                                        'maintenance' => 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-50 dark:bg-gray-800 dark:border-gray-700',
                                    ];
                                    $dotColors = [
                                        'available' => 'bg-success-500',
                                        'occupied' => 'bg-brand-500',
                                        'reserved' => 'bg-purple-500',
                                        'maintenance' => 'bg-gray-400',
                                    ];
                                @endphp
                                
                                @if($isAvailable)
                                    <a href="{{ route('orders.select-menu.table', ['table' => $table, 'type' => 'dine_in']) }}"
                                        class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="size-8 text-gray-600 dark:text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-800 dark:text-white/90">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $table->capacity }} kursi</span>
                                    </a>
                                @else
                                    <div class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="size-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-600 dark:text-gray-500">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-400 capitalize">{{ $table->status }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @empty
                    <!-- No areas -->
                @endforelse

                <!-- Tables without area -->
                @if($tablesWithoutArea->isNotEmpty())
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                            <svg class="size-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                            </svg>
                            Meja Lainnya
                        </h4>
                        
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach($tablesWithoutArea as $table)
                                @php
                                    $isAvailable = $table->status === 'available';
                                    $statusColors = [
                                        'available' => 'bg-success-50 border-success-200 hover:border-success-400 hover:bg-success-100 cursor-pointer dark:bg-success-500/10 dark:border-success-500/30 dark:hover:bg-success-500/20',
                                        'occupied' => 'bg-brand-50 border-brand-200 cursor-not-allowed opacity-75 dark:bg-brand-500/10 dark:border-brand-500/30',
                                        'reserved' => 'bg-purple-50 border-purple-200 cursor-not-allowed opacity-75 dark:bg-purple-500/10 dark:border-purple-500/30',
                                        'maintenance' => 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-50 dark:bg-gray-800 dark:border-gray-700',
                                    ];
                                    $dotColors = [
                                        'available' => 'bg-success-500',
                                        'occupied' => 'bg-brand-500',
                                        'reserved' => 'bg-purple-500',
                                        'maintenance' => 'bg-gray-400',
                                    ];
                                @endphp
                                
                                @if($isAvailable)
                                    <a href="{{ route('orders.select-menu.table', ['table' => $table, 'type' => 'dine_in']) }}"
                                        class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="size-8 text-gray-600 dark:text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-800 dark:text-white/90">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $table->capacity }} kursi</span>
                                    </a>
                                @else
                                    <div class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="size-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-600 dark:text-gray-500">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-400 capitalize">{{ $table->status }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($tableAreas->isEmpty() && $tablesWithoutArea->isEmpty())
                    <div class="text-center py-12">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                            <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada meja</h3>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tambahkan meja terlebih dahulu di menu Denah Meja</p>
                        <x-ui.button href="{{ route('tables.create') }}" variant="primary" class="mt-4">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Meja
                        </x-ui.button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-success-500"></span>
                    <span class="text-gray-600 dark:text-gray-400">Tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-brand-500"></span>
                    <span class="text-gray-600 dark:text-gray-400">Terisi</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    <span class="text-gray-600 dark:text-gray-400">Reserved</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                    <span class="text-gray-600 dark:text-gray-400">Maintenance</span>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
