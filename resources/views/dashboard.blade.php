<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    </x-slot>

    {{-- Welcome Section --}}
    <div class="mb-6">
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Selamat {{ now()->hour < 12 ? 'Pagi' : (now()->hour < 17 ? 'Siang' : 'Malam') }}, {{ Auth::user()->name }}! 👋</h2>
                    <p class="mt-1 text-primary-100">Panel kontrol untuk {{ Auth::user()->currentOutlet->name ?? 'outlet Anda' }}</p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-3">
                    <a href="{{ route('menu-items.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Menu
                    </a>
                    <a href="{{ route('tables.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Meja
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Today's Orders --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today_orders'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
                </div>
            </div>
        </div>

        {{-- Today's Revenue --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-secondary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['today_revenue'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Omset Hari Ini</p>
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-warning-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Pesanan Aktif</p>
                </div>
            </div>
        </div>

        {{-- Available Tables --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-accent-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['available_tables'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Meja Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Quick Actions --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Akses Cepat</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('orders.create') }}"
                           class="flex flex-col items-center p-4 bg-primary-50 rounded-xl hover:bg-primary-100 hover:text-primary-800 transition-colors group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow">
                                <svg class="w-6 h-6 text-primary-600 group-hover:text-primary-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-primary-700 group-hover:text-primary-800">Pesanan Baru</span>
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-secondary-50 hover:text-secondary-700 transition-colors group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-secondary-700">Riwayat Pesanan</span>
                        </a>

                        <a href="{{ route('tables.index') }}"
                           class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-accent-50 hover:text-accent-700 transition-colors group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-accent-700">Denah Meja</span>
                        </a>

                        <a href="{{ route('menu-items.index') }}"
                           class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-warning-50 hover:text-warning-700 transition-colors group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-warning-700">Daftar Menu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Status Overview --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Status Meja</h3>
                    <a href="{{ route('tables.index') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Available --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-secondary-500"></span>
                            <span class="text-sm text-gray-600">Tersedia</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['available_tables'] ?? 0 }}</span>
                    </div>

                    {{-- Occupied --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-primary-500"></span>
                            <span class="text-sm text-gray-600">Terisi</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['occupied_tables'] ?? 0 }}</span>
                    </div>

                    {{-- Reserved --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-accent-500"></span>
                            <span class="text-sm text-gray-600">Reserved</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['reserved_tables'] ?? 0 }}</span>
                    </div>

                    {{-- Maintenance --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <span class="text-sm text-gray-600">Maintenance</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['maintenance_tables'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Outlet Info --}}
            @if(Auth::user()->currentOutlet)
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Info Outlet</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ Auth::user()->currentOutlet->name }}</h4>
                            <p class="text-sm text-gray-500">{{ Auth::user()->currentOutlet->address ?? 'Alamat belum diatur' }}</p>
                        </div>
                    </div>
                    @if(Auth::user()->currentOutlet->phone)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ Auth::user()->currentOutlet->phone }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
