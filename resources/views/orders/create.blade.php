<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Buat Pesanan Baru</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('orders.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Order Type Selection --}}
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Tipe Pesanan</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.create', ['type' => 'dine_in']) }}"
                   class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'dine_in' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 hover:border-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    <span class="font-medium">Dine In</span>
                </a>
                <a href="{{ route('orders.select-menu', ['type' => 'takeaway']) }}"
                   class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'takeaway' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 hover:border-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="font-medium">Takeaway</span>
                </a>
                <a href="{{ route('orders.select-menu', ['type' => 'delivery']) }}"
                   class="flex items-center gap-3 px-5 py-3 rounded-xl border-2 transition-all {{ $orderType === 'delivery' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 hover:border-gray-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    <span class="font-medium">Delivery</span>
                </a>
            </div>
        </div>
    </div>

    @if($orderType === 'dine_in')
        {{-- Table Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Pilih Meja</h3>
                <p class="text-sm text-gray-500 mt-1">Klik pada meja yang tersedia untuk memilih</p>
            </div>
            
            <div class="p-6 space-y-8">
                {{-- Tables by Area --}}
                @forelse($tableAreas as $area)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        'available' => 'bg-secondary-50 border-secondary-200 hover:border-secondary-400 hover:bg-secondary-100 cursor-pointer',
                                        'occupied' => 'bg-primary-50 border-primary-200 cursor-not-allowed opacity-75',
                                        'reserved' => 'bg-accent-50 border-accent-200 cursor-not-allowed opacity-75',
                                        'maintenance' => 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-50',
                                    ];
                                    $dotColors = [
                                        'available' => 'bg-secondary-500',
                                        'occupied' => 'bg-primary-500',
                                        'reserved' => 'bg-accent-500',
                                        'maintenance' => 'bg-gray-400',
                                    ];
                                @endphp
                                
                                @if($isAvailable)
                                    <a href="{{ route('orders.select-menu.table', ['table' => $table, 'type' => 'dine_in']) }}"
                                       class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="w-8 h-8 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-900">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-500">{{ $table->capacity }} kursi</span>
                                    </a>
                                @else
                                    <div class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-600">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-400 capitalize">{{ $table->status }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @empty
                    {{-- No areas --}}
                @endforelse

                {{-- Tables without area --}}
                @if($tablesWithoutArea->isNotEmpty())
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                            </svg>
                            Meja Lainnya
                        </h4>
                        
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach($tablesWithoutArea as $table)
                                @php
                                    $isAvailable = $table->status === 'available';
                                    $statusColors = [
                                        'available' => 'bg-secondary-50 border-secondary-200 hover:border-secondary-400 hover:bg-secondary-100 cursor-pointer',
                                        'occupied' => 'bg-primary-50 border-primary-200 cursor-not-allowed opacity-75',
                                        'reserved' => 'bg-accent-50 border-accent-200 cursor-not-allowed opacity-75',
                                        'maintenance' => 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-50',
                                    ];
                                    $dotColors = [
                                        'available' => 'bg-secondary-500',
                                        'occupied' => 'bg-primary-500',
                                        'reserved' => 'bg-accent-500',
                                        'maintenance' => 'bg-gray-400',
                                    ];
                                @endphp
                                
                                @if($isAvailable)
                                    <a href="{{ route('orders.select-menu.table', ['table' => $table, 'type' => 'dine_in']) }}"
                                       class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="w-8 h-8 text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-900">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-500">{{ $table->capacity }} kursi</span>
                                    </a>
                                @else
                                    <div class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 {{ $statusColors[$table->status] }}">
                                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full {{ $dotColors[$table->status] }}"></span>
                                        <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                                        </svg>
                                        <span class="font-bold text-gray-600">{{ $table->number }}</span>
                                        <span class="text-xs text-gray-400 capitalize">{{ $table->status }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($tableAreas->isEmpty() && $tablesWithoutArea->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8v10M18 8v10"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-500">Belum ada meja</h3>
                        <p class="text-sm text-gray-400 mt-1">Tambahkan meja terlebih dahulu di menu Denah Meja</p>
                        <a href="{{ route('tables.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Meja
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Legend --}}
        <div class="mt-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-secondary-500"></span>
                    <span class="text-gray-600">Tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-primary-500"></span>
                    <span class="text-gray-600">Terisi</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-accent-500"></span>
                    <span class="text-gray-600">Reserved</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                    <span class="text-gray-600">Maintenance</span>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
