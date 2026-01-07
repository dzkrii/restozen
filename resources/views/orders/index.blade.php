<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
            <a href="{{ route('orders.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Pesanan Baru
            </a>
        </div>
    </x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayOrders }}</p>
                    <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-secondary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-warning-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                    <p class="text-sm text-gray-500">Pesanan Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-accent-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ now()->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center gap-4 p-4 overflow-x-auto">
            <a href="{{ route('orders.index', ['status' => 'all', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') || request('status') === 'all' ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'pending' ? 'bg-warning-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Pending
            </a>
            <a href="{{ route('orders.index', ['status' => 'confirmed', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'confirmed' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Dikonfirmasi
            </a>
            <a href="{{ route('orders.index', ['status' => 'preparing', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'preparing' ? 'bg-accent-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Dimasak
            </a>
            <a href="{{ route('orders.index', ['status' => 'ready', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'ready' ? 'bg-secondary-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Siap
            </a>
            <a href="{{ route('orders.index', ['status' => 'completed', 'date' => request('date', today()->format('Y-m-d'))]) }}"
               class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'completed' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Selesai
            </a>

            <div class="flex-1"></div>

            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="date"
                       name="date"
                       value="{{ request('date', today()->format('Y-m-d')) }}"
                       onchange="this.form.submit()"
                       class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </form>
        </div>
    </div>

    {{-- Orders Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($orders as $order)
            @php
                $statusConfig = [
                    'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'label' => 'Pending'],
                    'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Dikonfirmasi'],
                    'preparing' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700', 'label' => 'Dimasak'],
                    'ready' => ['bg' => 'bg-secondary-100', 'text' => 'text-secondary-700', 'label' => 'Siap'],
                    'served' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700', 'label' => 'Disajikan'],
                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
                    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Dibatalkan'],
                ];
                $status = $statusConfig[$order->status] ?? $statusConfig['pending'];
            @endphp

            <a href="{{ route('orders.show', $order) }}"
               class="block bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-primary-200 transition-all overflow-hidden">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-500">
                                @if($order->table)
                                    Meja {{ $order->table->number }}
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                @endif
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $order->created_at->format('H:i') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $order->items->count() }} item
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-400">{{ $order->user?->name ?? 'System' }}</span>
                        <span class="font-bold text-primary-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500">Belum ada pesanan</h3>
                <p class="text-sm text-gray-400 mt-1">Buat pesanan baru untuk memulai</p>
                <a href="{{ route('orders.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pesanan
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</x-app-layout>
