<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="kitchenDisplay" x-init="init">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restozen') }} - Kitchen Display</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Hidden audio element -->
    <audio id="notificationSound" preload="auto">
        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
    </audio>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 dark:bg-gray-950 dark:text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10 dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white/90">Kitchen Display System</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pesanan masuk</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-sm font-medium text-brand-700 dark:bg-brand-500/15 dark:text-brand-400">
                    {{ $orders->count() }} Pesanan Aktif
                </span>

                <!-- New Order Notification Badge -->
                <span 
                    x-show="newOrdersCount > 0"
                    x-transition
                    class="inline-flex items-center rounded-full bg-success-500 px-3 py-1 text-sm font-bold text-white animate-bounce">
                    +<span x-text="newOrdersCount"></span> Baru!
                </span>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Sound Toggle -->
                <button 
                    @click="toggleSound"
                    :class="soundEnabled ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'"
                    class="px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 hover:opacity-80 transition-all">
                    <svg x-show="soundEnabled" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.586A2 2 0 014 14V10a2 2 0 011-1.732l7-3.5a1 1 0 011.5.866v13.732a1 1 0 01-1.5.866l-7-3.5z"/>
                    </svg>
                    <svg x-show="!soundEnabled" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15.586A2 2 0 014 14V10a2 2 0 011-1.732l7-3.5a1 1 0 011.5.866v13.732a1 1 0 01-1.5.866l-7-3.5zM17 12h6"/>
                    </svg>
                    <span x-text="soundEnabled ? 'Suara: ON' : 'Suara: OFF'"></span>
                </button>

                <div id="clock" class="text-xl font-mono text-gray-600 dark:text-gray-400 font-bold"></div>
                
                <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Segarkan
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="flex flex-col items-center justify-center h-96 text-gray-400 dark:text-gray-500">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <svg class="size-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-xl font-medium text-gray-600 dark:text-gray-400">Tidak ada pesanan aktif</p>
                    <p class="text-sm mt-1">Menunggu pesanan baru masuk!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($orders as $order)
                        @include('kitchen.partials.order-card', ['order' => $order])
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <script>
        // Alpine.js Component for Kitchen Display
        document.addEventListener('alpine:init', () => {
            Alpine.data('kitchenDisplay', () => ({
                soundEnabled: localStorage.getItem('kitchen_sound') !== 'false',
                lastOrderId: {{ $orders->max('id') ?? 0 }},
                newOrdersCount: 0,
                checkInterval: null,
                hasInteracted: false,

                init() {
                    // Start polling for new orders
                    this.checkInterval = setInterval(() => {
                        this.checkNewOrders();
                    }, 5000); // Check every 5 seconds

                    // Mark as interacted after first click anywhere
                    document.body.addEventListener('click', () => {
                        this.hasInteracted = true;
                    }, { once: true });
                },

                async checkNewOrders() {
                    try {
                        const response = await fetch(`{{ route('kitchen.check-new') }}?last_order_id=${this.lastOrderId}`);
                        const data = await response.json();

                        if (data.has_new_orders) {
                            this.newOrdersCount = data.new_orders_count;
                            this.lastOrderId = data.latest_order_id;

                            // Play notification sound
                            if (this.soundEnabled && this.hasInteracted) {
                                this.playNotificationSound();
                            }

                            // Show browser notification
                            this.showBrowserNotification(data.new_orders);

                            // Auto reload after 2 seconds to show new orders
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    } catch (error) {
                        console.error('Error checking new orders:', error);
                    }
                },

                playNotificationSound() {
                    const audio = document.getElementById('notificationSound');
                    if (audio) {
                        audio.play().catch(err => {
                            console.log('Could not play sound:', err);
                        });
                    }
                },

                showBrowserNotification(orders) {
                    if ("Notification" in window && Notification.permission === "granted") {
                        const order = orders[0];
                        new Notification("Pesanan Baru Masuk!", {
                            body: `Order #${order.order_number} dari ${order.table}\n${order.items_count} item`,
                            icon: '/favicon.ico',
                            badge: '/favicon.ico'
                        });
                    }
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                    localStorage.setItem('kitchen_sound', this.soundEnabled ? 'true' : 'false');

                    // Test sound when enabling
                    if (this.soundEnabled) {
                        this.hasInteracted = true;
                        this.playNotificationSound();
                    }
                },

                destroy() {
                    if (this.checkInterval) {
                        clearInterval(this.checkInterval);
                    }
                }
            }));
        });

        // Update Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clockEl = document.getElementById('clock');
            if (clockEl) clockEl.textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Request notification permission on load
        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission();
        }
    </script>
</body>
</html>
