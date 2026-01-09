<x-app-layout>
    <x-slot name="title">{{ $order->order_number }}</x-slot>

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
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $order->order_number }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(!in_array($order->status, ['completed', 'cancelled']))
                <x-ui.button href="{{ route('orders.edit', $order) }}" variant="success">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Item
                </x-ui.button>
            @endif
            <x-ui.button href="{{ route('orders.receipt', $order) }}" variant="outline" target="_blank">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Struk
            </x-ui.button>
        </div>
    </div>

    @if(session('success'))
        <x-ui.alert variant="success" class="mb-6">
            {{ session('success') }}
        </x-ui.alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Informasi Pesanan</h3>
                    @php
                        // Simplified status config (4 main statuses)
                        $statusConfig = [
                            'confirmed' => ['bg' => 'bg-brand-50 dark:bg-brand-500/15', 'text' => 'text-brand-700 dark:text-brand-400', 'label' => 'Menunggu Dapur'],
                            'preparing' => ['bg' => 'bg-purple-50 dark:bg-purple-500/15', 'text' => 'text-purple-700 dark:text-purple-400', 'label' => 'Sedang Dimasak'],
                            'ready' => ['bg' => 'bg-success-50 dark:bg-success-500/15', 'text' => 'text-success-700 dark:text-success-400', 'label' => 'Siap Saji'],
                            'completed' => ['bg' => 'bg-success-50 dark:bg-success-500/15', 'text' => 'text-success-700 dark:text-success-400', 'label' => 'Selesai'],
                            'cancelled' => ['bg' => 'bg-error-50 dark:bg-error-500/15', 'text' => 'text-error-700 dark:text-error-400', 'label' => 'Dibatalkan'],
                        ];
                        $status = $statusConfig[$order->status] ?? $statusConfig['confirmed'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                        {{ $status['label'] }}
                    </span>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Meja</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ $order->table?->number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Pesanan</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Pelanggan</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ $order->customer_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">No. HP</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ $order->customer_phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tamu</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ $order->guest_count }} orang</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Kasir</dt>
                            <dd class="font-medium text-gray-800 dark:text-white/90">{{ $order->user?->name ?? 'System' }}</dd>
                        </div>
                    </dl>
                    @if($order->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Catatan</dt>
                            <dd class="text-gray-700 dark:text-gray-300">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            @if($item->menuItem?->image)
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}"
                                    alt="{{ $item->menu_item_name }}"
                                    class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="size-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 dark:text-white/90">{{ $item->menu_item_name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-gray-400 mt-1">{{ $item->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-800 dark:text-white/90">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                @php
                                    $itemStatusConfig = [
                                        'pending' => ['class' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400', 'label' => 'Menunggu'],
                                        'preparing' => ['class' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400', 'label' => 'Dimasak'],
                                        'ready' => ['class' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400', 'label' => 'Siap'],
                                        'served' => ['class' => 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400', 'label' => 'Selesai'],
                                        'cancelled' => ['class' => 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400', 'label' => 'Batal'],
                                    ];
                                    $itemStatus = $itemStatusConfig[$item->status] ?? ['class' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400', 'label' => ucfirst($item->status)];
                                @endphp
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium {{ $itemStatus['class'] }}">
                                    {{ $itemStatus['label'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                            <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Pajak</span>
                                <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($order->service_charge > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Service Charge</span>
                                <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Diskon</span>
                                <span class="text-error-600 dark:text-error-400">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-800 dark:text-white/90">Total</span>
                            <span class="text-brand-600 dark:text-brand-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Update -->
            @if(!in_array($order->status, ['completed', 'cancelled']))
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <h3 class="font-semibold text-gray-800 dark:text-white/90">Update Status</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @php
                            // Simplified status flow:
                            // confirmed → preparing → ready → completed
                            $statusFlow = [
                                'confirmed' => ['preparing' => ['label' => 'Mulai Masak', 'color' => 'purple', 'icon' => 'fire']],
                                'preparing' => ['ready' => ['label' => 'Siap Saji', 'color' => 'success', 'icon' => 'check']],
                                'ready' => ['completed' => ['label' => 'Selesai', 'color' => 'success', 'icon' => 'check-circle']],
                            ];
                            $nextStatuses = $statusFlow[$order->status] ?? [];
                        @endphp

                        {{-- Info based on current status --}}
                        @if($order->status === 'confirmed')
                            <div class="bg-brand-50 dark:bg-brand-500/10 rounded-lg p-3 mb-3">
                                <p class="text-sm text-brand-700 dark:text-brand-400">
                                    <svg class="size-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pesanan menunggu diproses oleh dapur
                                </p>
                            </div>
                        @elseif($order->status === 'preparing')
                            <div class="bg-purple-50 dark:bg-purple-500/10 rounded-lg p-3 mb-3">
                                <p class="text-sm text-purple-700 dark:text-purple-400">
                                    <svg class="size-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                    Dapur sedang memasak pesanan ini
                                </p>
                            </div>
                        @elseif($order->status === 'ready')
                            <div class="bg-success-50 dark:bg-success-500/10 rounded-lg p-3 mb-3">
                                <p class="text-sm text-success-700 dark:text-success-400">
                                    <svg class="size-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Makanan siap! Proses pembayaran untuk menyelesaikan.
                                </p>
                            </div>
                        @endif

                        @foreach($nextStatuses as $nextStatus => $config)
                            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                @if($nextStatus === 'completed' && !$order->isPaid())
                                    {{-- If completing, suggest payment first --}}
                                    <x-ui.button href="{{ route('payments.create', $order) }}" variant="success" class="w-full justify-center">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Proses Pembayaran & Selesai
                                    </x-ui.button>
                                @else
                                    <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                                        {{ $config['label'] }}
                                    </x-ui.button>
                                @endif
                            </form>
                        @endforeach

                        {{-- Cancel button --}}
                        @if($order->status !== 'cancelled')
                            <form action="{{ route('orders.update-status', $order) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                    class="w-full py-2.5 bg-error-50 hover:bg-error-100 text-error-600 rounded-lg font-medium transition-colors dark:bg-error-500/10 dark:hover:bg-error-500/20 dark:text-error-400">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Payment -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Pembayaran</h3>
                </div>
                <div class="p-6">
                    @if($order->payments->isNotEmpty())
                        <div class="space-y-3 mb-4">
                            @foreach($order->payments as $payment)
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($payment->payment_method) }}</span>
                                        @if($payment->reference_number)
                                            <span class="text-gray-400 text-xs block">{{ $payment->reference_number }}</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="font-medium text-gray-800 dark:text-white/90">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        @if($payment->status === 'completed')
                                            <svg class="size-4 text-success-500 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($order->isPaid())
                        <div class="bg-success-50 text-success-700 px-4 py-3 rounded-lg text-center font-medium dark:bg-success-500/10 dark:text-success-400">
                            <svg class="size-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lunas
                        </div>
                    @elseif(!in_array($order->status, ['cancelled']))
                        <x-ui.button href="{{ route('payments.create', $order) }}" variant="success" class="w-full justify-center">
                            Proses Pembayaran
                        </x-ui.button>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="size-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">Pesanan Dibuat</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i:s') }}</p>
                            </div>
                        </div>
                        @if($order->confirmed_at)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="size-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">Dikonfirmasi</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->confirmed_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($order->completed_at)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-success-100 dark:bg-success-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="size-4 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">Selesai</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->completed_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
