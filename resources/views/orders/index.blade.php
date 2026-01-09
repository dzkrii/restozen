<x-app-layout>
    <x-slot name="title">Daftar Pesanan</x-slot>

    @php
        $user = auth()->user();
        $outlet = $user->current_outlet;
        $userCapabilities = $outlet ? $user->capabilitiesAt($outlet) : [];
        $isWaiter = in_array('waiter', $userCapabilities);
        $isCashier = in_array('cashier', $userCapabilities);
        $isKitchen = in_array('kitchen', $userCapabilities);
        
        // Determine view mode based on query or capability
        $viewMode = request('view', $isCashier && !$isWaiter ? 'cashier' : 'waiter');
    @endphp

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                @if($viewMode === 'cashier')
                    Kasir - Proses Pembayaran
                @else
                    Daftar Pesanan
                @endif
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($viewMode === 'cashier')
                    Pesanan yang siap untuk diproses pembayarannya
                @else
                    Kelola pesanan masuk dan riwayat transaksi
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            {{-- View Mode Toggle (if user has both capabilities) --}}
            @if($isWaiter && $isCashier)
                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['view' => 'waiter'])) }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $viewMode === 'waiter' ? 'bg-white dark:bg-gray-700 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                        <span class="hidden sm:inline">Pelayan</span>
                        <svg class="sm:hidden size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </a>
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['view' => 'cashier'])) }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $viewMode === 'cashier' ? 'bg-white dark:bg-gray-700 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                        <span class="hidden sm:inline">Kasir</span>
                        <svg class="sm:hidden size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </a>
                </div>
            @endif

            @if($isWaiter)
                <x-ui.button href="{{ route('orders.create') }}" variant="primary">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">Pesanan Baru</span>
                </x-ui.button>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @if($viewMode === 'cashier')
            {{-- Cashier Stats --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-warning-50 dark:bg-warning-500/10">
                        <svg class="size-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $unpaidCount ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum Dibayar</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10">
                        <svg class="size-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $paidTodayCount ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lunas Hari Ini</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="size-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-800 dark:text-white/90">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                        <svg class="size-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-800 dark:text-white/90">{{ now()->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        @else
            {{-- Waiter Stats --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="size-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $todayOrders }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan Hari Ini</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-500/10">
                        <svg class="size-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $pendingCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan Aktif</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10">
                        <svg class="size-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ $readyCount ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Siap Diantar</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                        <svg class="size-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-800 dark:text-white/90">{{ now()->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 p-4 overflow-x-auto">
            @if($viewMode === 'cashier')
                {{-- Cashier Filters: Focus on payment status --}}
                <a href="{{ route('orders.index', ['view' => 'cashier', 'payment' => 'unpaid', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('payment', 'unpaid') === 'unpaid' 
                        ? 'bg-warning-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Belum Bayar
                    </span>
                </a>
                <a href="{{ route('orders.index', ['view' => 'cashier', 'payment' => 'paid', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('payment') === 'paid' 
                        ? 'bg-success-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    <span class="flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sudah Lunas
                    </span>
                </a>
                <a href="{{ route('orders.index', ['view' => 'cashier', 'payment' => 'all', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('payment') === 'all' 
                        ? 'bg-brand-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Semua
                </a>
            @else
                {{-- Waiter Filters: Focus on order status --}}
                <a href="{{ route('orders.index', ['view' => 'waiter', 'status' => 'all', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') || request('status') === 'all' 
                        ? 'bg-brand-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Semua
                </a>
                <a href="{{ route('orders.index', ['view' => 'waiter', 'status' => 'confirmed', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'confirmed' 
                        ? 'bg-brand-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Menunggu Dapur
                </a>
                <a href="{{ route('orders.index', ['view' => 'waiter', 'status' => 'preparing', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'preparing' 
                        ? 'bg-purple-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Sedang Dimasak
                </a>
                <a href="{{ route('orders.index', ['view' => 'waiter', 'status' => 'ready', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'ready' 
                        ? 'bg-success-500 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Siap Diantar
                </a>
                <a href="{{ route('orders.index', ['view' => 'waiter', 'status' => 'completed', 'date' => request('date', today()->format('Y-m-d'))]) }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'completed' 
                        ? 'bg-gray-600 text-white' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Selesai
                </a>
            @endif

            <div class="flex-1"></div>

            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="view" value="{{ $viewMode }}">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="hidden" name="payment" value="{{ request('payment', 'unpaid') }}">
                <input type="date"
                    name="date"
                    value="{{ request('date', today()->format('Y-m-d')) }}"
                    onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white dark:bg-gray-900 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </form>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($orders as $order)
            @php
                $isPaid = $order->isPaid();
                
                // Order status config
                $statusConfig = [
                    'confirmed' => ['bg' => 'bg-brand-50 dark:bg-brand-500/15', 'text' => 'text-brand-700 dark:text-brand-400', 'label' => 'Menunggu Dapur', 'icon' => 'clock'],
                    'preparing' => ['bg' => 'bg-purple-50 dark:bg-purple-500/15', 'text' => 'text-purple-700 dark:text-purple-400', 'label' => 'Dimasak', 'icon' => 'fire'],
                    'ready' => ['bg' => 'bg-success-50 dark:bg-success-500/15', 'text' => 'text-success-700 dark:text-success-400', 'label' => 'Siap Diantar', 'icon' => 'check'],
                    'completed' => ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-700 dark:text-gray-400', 'label' => 'Selesai', 'icon' => 'check-circle'],
                    'cancelled' => ['bg' => 'bg-error-50 dark:bg-error-500/15', 'text' => 'text-error-700 dark:text-error-400', 'label' => 'Batal', 'icon' => 'x'],
                ];
                $status = $statusConfig[$order->status] ?? $statusConfig['confirmed'];
                
                // Payment status config
                $paymentStatus = $isPaid 
                    ? ['bg' => 'bg-success-100 dark:bg-success-500/20', 'text' => 'text-success-700 dark:text-success-400', 'label' => 'Lunas', 'icon' => 'check']
                    : ['bg' => 'bg-warning-100 dark:bg-warning-500/20', 'text' => 'text-warning-700 dark:text-warning-400', 'label' => 'Belum Bayar', 'icon' => 'clock'];
            @endphp

            <a href="{{ route('orders.show', $order) }}"
                class="block rounded-xl border transition-all hover:shadow-lg overflow-hidden {{ !$isPaid && $viewMode === 'cashier' ? 'border-warning-300 dark:border-warning-700 bg-warning-50/50 dark:bg-warning-500/5' : 'border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]' }} hover:border-brand-300 dark:hover:border-brand-700">
                
                {{-- Card Header with Status Badges --}}
                <div class="px-5 pt-5 pb-3">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white/90">{{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($order->table)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/>
                                        </svg>
                                        Meja {{ $order->table->number }}
                                    </span>
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                @endif
                            </p>
                        </div>
                        
                        {{-- Show different primary badge based on view mode --}}
                        @if($viewMode === 'cashier')
                            {{-- Cashier View: Payment status is primary --}}
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }}">
                                {{ $paymentStatus['label'] }}
                            </span>
                        @else
                            {{-- Waiter View: Order status is primary --}}
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        @endif
                    </div>

                    {{-- Secondary Status Row --}}
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if($viewMode === 'cashier')
                            {{-- Cashier View: Show order status as secondary --}}
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs {{ $status['bg'] }} {{ $status['text'] }}">
                                @if($order->status === 'ready')
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($order->status === 'preparing')
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                                {{ $status['label'] }}
                            </span>
                        @else
                            {{-- Waiter View: Show payment status as secondary --}}
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }}">
                                @if($isPaid)
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/>
                                    </svg>
                                @endif
                                {{ $paymentStatus['label'] }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Order Info --}}
                <div class="px-5 pb-3">
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $order->created_at->format('H:i') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $order->items->count() }} item
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between {{ !$isPaid && $viewMode === 'cashier' ? 'bg-warning-50/50 dark:bg-warning-500/5' : 'bg-gray-50/50 dark:bg-gray-900/50' }}">
                    <span class="text-xs text-gray-400">{{ $order->user?->name ?? 'System' }}</span>
                    <span class="font-bold {{ !$isPaid ? 'text-warning-600 dark:text-warning-400' : 'text-brand-600 dark:text-brand-400' }}">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Quick Action for Cashier --}}
                @if($viewMode === 'cashier' && !$isPaid && $isCashier)
                    <div class="px-5 py-2 bg-warning-100 dark:bg-warning-500/10 border-t border-warning-200 dark:border-warning-500/20">
                        <span class="text-xs font-medium text-warning-700 dark:text-warning-400 flex items-center gap-1">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Klik untuk proses pembayaran
                        </span>
                    </div>
                @endif
            </a>
        @empty
            <div class="col-span-full rounded-xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                    @if($viewMode === 'cashier')
                        <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    @endif
                </div>
                <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">
                    @if($viewMode === 'cashier')
                        @if(request('payment') === 'unpaid' || !request('payment'))
                            Tidak ada pesanan yang perlu dibayar
                        @else
                            Belum ada transaksi hari ini
                        @endif
                    @else
                        Belum ada pesanan
                    @endif
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @if($viewMode === 'cashier')
                        Semua pesanan sudah lunas 🎉
                    @else
                        Buat pesanan baru untuk memulai
                    @endif
                </p>
                @if($isWaiter && $viewMode !== 'cashier')
                    <x-ui.button href="{{ route('orders.create') }}" variant="primary" class="mt-4">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pesanan
                    </x-ui.button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</x-app-layout>
