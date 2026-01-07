<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.index') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(!in_array($order->status, ['completed', 'cancelled']))
                    <a href="{{ route('orders.edit', $order) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-secondary-600 hover:bg-secondary-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Item
                    </a>
                @endif
                <a href="{{ route('orders.receipt', $order) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Struk
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-secondary-50 border border-secondary-200 text-secondary-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Informasi Pesanan</h3>
                    @php
                        $statusConfig = [
                            'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'label' => 'Pending'],
                            'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Dikonfirmasi'],
                            'preparing' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700', 'label' => 'Dimasak'],
                            'ready' => ['bg' => 'bg-secondary-100', 'text' => 'text-secondary-700', 'label' => 'Siap Saji'],
                            'served' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700', 'label' => 'Disajikan'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Dibatalkan'],
                        ];
                        $status = $statusConfig[$order->status] ?? $statusConfig['pending'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                        {{ $status['label'] }}
                    </span>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Meja</dt>
                            <dd class="font-medium text-gray-900">{{ $order->table?->number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tipe Pesanan</dt>
                            <dd class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Pelanggan</dt>
                            <dd class="font-medium text-gray-900">{{ $order->customer_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">No. HP</dt>
                            <dd class="font-medium text-gray-900">{{ $order->customer_phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Jumlah Tamu</dt>
                            <dd class="font-medium text-gray-900">{{ $order->guest_count }} orang</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Kasir</dt>
                            <dd class="font-medium text-gray-900">{{ $order->user?->name ?? 'System' }}</dd>
                        </div>
                    </dl>
                    @if($order->notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <dt class="text-sm text-gray-500 mb-1">Catatan</dt>
                            <dd class="text-gray-700">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                            @if($item->menuItem?->image)
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}"
                                     alt="{{ $item->menu_item_name }}"
                                     class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->menu_item_name }}</h4>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-gray-400 mt-1">{{ $item->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                @php
                                    $itemStatusConfig = [
                                        'pending' => 'bg-warning-100 text-warning-700',
                                        'preparing' => 'bg-accent-100 text-accent-700',
                                        'ready' => 'bg-secondary-100 text-secondary-700',
                                        'served' => 'bg-primary-100 text-primary-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium {{ $itemStatusConfig[$item->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Pajak</span>
                                <span class="text-gray-700">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($order->service_charge > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Service Charge</span>
                                <span class="text-gray-700">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Diskon</span>
                                <span class="text-red-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Total</span>
                            <span class="text-primary-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Update --}}
            @if(!in_array($order->status, ['completed', 'cancelled']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ updating: false }">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Update Status</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @php
                            $statusFlow = [
                                'pending' => ['confirmed' => 'Konfirmasi Pesanan'],
                                'confirmed' => ['preparing' => 'Mulai Masak'],
                                'preparing' => ['ready' => 'Siap Saji'],
                                'ready' => ['served' => 'Disajikan'],
                                'served' => ['completed' => 'Selesai'],
                            ];
                            $nextStatuses = $statusFlow[$order->status] ?? [];
                        @endphp

                        @foreach($nextStatuses as $nextStatus => $label)
                            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                <button type="submit"
                                        class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                                    {{ $label }}
                                </button>
                            </form>
                        @endforeach

                        @if($order->status !== 'cancelled')
                            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                        class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-medium transition-colors">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Pembayaran</h3>
                </div>
                <div class="p-6">
                    @if($order->payments->isNotEmpty())
                        <div class="space-y-3 mb-4">
                            @foreach($order->payments as $payment)
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">{{ ucfirst($payment->payment_method) }}</span>
                                        @if($payment->reference_number)
                                            <span class="text-gray-400 text-xs block">{{ $payment->reference_number }}</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        @if($payment->status === 'completed')
                                            <svg class="w-4 h-4 text-green-500 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($order->isPaid())
                        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-center font-medium">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lunas
                        </div>
                    @elseif(!in_array($order->status, ['cancelled']))
                        <a href="{{ route('payments.create', $order) }}"
                           class="block w-full py-3 bg-secondary-600 hover:bg-secondary-700 text-white rounded-lg font-medium text-center transition-colors">
                            Proses Pembayaran
                        </a>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i:s') }}</p>
                            </div>
                        </div>
                        @if($order->confirmed_at)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dikonfirmasi</p>
                                    <p class="text-xs text-gray-500">{{ $order->confirmed_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($order->completed_at)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Selesai</p>
                                    <p class="text-xs text-gray-500">{{ $order->completed_at->format('d M Y, H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
