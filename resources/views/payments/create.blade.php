<x-app-layout>
    <x-slot name="title">Pembayaran - {{ $order->order_number }}</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.show', $order) }}"
               class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Pembayaran</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $order->order_number }}</p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <x-ui.alert variant="error" class="mb-6">
            {{ session('error') }}
        </x-ui.alert>
    @endif

    <div class="max-w-3xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6" x-data="paymentForm()">
            
            {{-- Order Summary - Left Side --}}
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Ringkasan</h3>
                </div>

                {{-- Order Items --}}
                <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-[250px] overflow-y-auto">
                    @foreach($order->items as $item)
                        <div class="px-5 py-2.5 flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-600 dark:text-gray-400">{{ $item->quantity }}x</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $item->menu_item_name }}</span>
                            </div>
                            <span class="font-medium text-gray-800 dark:text-white/90">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="px-5 py-4 bg-brand-50 dark:bg-brand-500/10 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800 dark:text-white/90">Total Tagihan</span>
                        <span class="text-2xl font-bold text-brand-600 dark:text-brand-400">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                    </div>
                    @if($paidAmount > 0)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sudah dibayar: Rp {{ number_format($paidAmount, 0, ',', '.') }}</p>
                    @endif
                </div>

                {{-- Order Info --}}
                <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        @if($order->table)
                            <span>Meja {{ $order->table->number }}</span>
                            <span>•</span>
                        @endif
                        <span>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                        @if($order->customer_name)
                            <span>•</span>
                            <span>{{ $order->customer_name }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Form - Right Side --}}
            <div class="lg:col-span-3 rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                    <h3 class="font-semibold text-gray-800 dark:text-white/90">Metode Pembayaran</h3>
                </div>

                <form action="{{ route('payments.store', $order) }}" method="POST" class="p-5 space-y-5">
                    @csrf
                    {{-- Hidden amount field - always the remaining amount --}}
                    <input type="hidden" name="amount" value="{{ $remainingAmount }}">

                    {{-- Payment Method Selection --}}
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="sr-only peer" checked>
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all group-hover:border-gray-300 dark:group-hover:border-gray-600">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 peer-checked:bg-brand-100 dark:peer-checked:bg-brand-500/20 transition-colors mb-2">
                                    <svg class="size-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cash</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="transfer" x-model="method" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all group-hover:border-gray-300 dark:group-hover:border-gray-600">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 peer-checked:bg-brand-100 dark:peer-checked:bg-brand-500/20 transition-colors mb-2">
                                    <svg class="size-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Transfer</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="qris" x-model="method" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all group-hover:border-gray-300 dark:group-hover:border-gray-600">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 peer-checked:bg-brand-100 dark:peer-checked:bg-brand-500/20 transition-colors mb-2">
                                    <svg class="size-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">QRIS</span>
                            </div>
                        </label>
                    </div>

                    {{-- Cash Payment Section --}}
                    <div x-show="method === 'cash'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uang Diterima</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                                <input type="number"
                                       name="cash_received"
                                       x-model="cashReceived"
                                       min="0"
                                       step="1000"
                                       class="w-full pl-12 pr-4 py-3.5 text-xl font-bold border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-colors">
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <button type="button" @click="cashReceived = totalAmount" 
                                        class="px-4 py-2 bg-brand-50 hover:bg-brand-100 dark:bg-brand-500/10 dark:hover:bg-brand-500/20 rounded-lg text-sm font-medium text-brand-700 dark:text-brand-400 transition-colors border border-brand-200 dark:border-brand-500/30">
                                    Uang Pas
                                </button>
                                <button type="button" @click="cashReceived = 50000" 
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                    50rb
                                </button>
                                <button type="button" @click="cashReceived = 100000" 
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                    100rb
                                </button>
                                <button type="button" @click="cashReceived = 150000" 
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                    150rb
                                </button>
                                <button type="button" @click="cashReceived = 200000" 
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                                    200rb
                                </button>
                            </div>
                        </div>

                        {{-- Change Display --}}
                        <div x-show="cashReceived >= totalAmount && cashReceived > 0" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100"
                             class="p-4 bg-success-50 dark:bg-success-500/10 rounded-xl border border-success-200 dark:border-success-500/30">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <svg class="size-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-success-700 dark:text-success-400 font-semibold">Kembalian</span>
                                </div>
                                <span class="text-3xl font-bold text-success-600 dark:text-success-400" x-text="'Rp ' + formatNumber(cashReceived - totalAmount)"></span>
                            </div>
                        </div>

                        {{-- Not enough cash warning --}}
                        <div x-show="cashReceived > 0 && cashReceived < totalAmount" 
                             x-transition
                             class="p-3 bg-error-50 dark:bg-error-500/10 rounded-xl border border-error-200 dark:border-error-500/30">
                            <p class="text-sm text-error-700 dark:text-error-400 flex items-center gap-2">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Uang kurang Rp <span x-text="formatNumber(totalAmount - cashReceived)"></span>
                            </p>
                        </div>
                    </div>

                    {{-- Non-Cash Payment Section --}}
                    <div x-show="method === 'transfer' || method === 'qris'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Pastikan pelanggan sudah melakukan pembayaran sebesar 
                                <span class="font-bold text-gray-800 dark:text-white">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Referensi (Opsional)</label>
                            <input type="text"
                                   name="reference_number"
                                   placeholder="Masukkan nomor referensi transaksi"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 transition-colors">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            :disabled="method === 'cash' && cashReceived < totalAmount"
                            :class="method === 'cash' && cashReceived < totalAmount ? 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed' : 'bg-success-500 hover:bg-success-600 shadow-lg shadow-success-500/25'"
                            class="w-full py-4 rounded-xl text-white font-bold text-lg transition-all flex items-center justify-center gap-2">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="method === 'cash' ? 'Bayar Tunai' : (method === 'transfer' ? 'Konfirmasi Transfer' : 'Konfirmasi QRIS')"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function paymentForm() {
            return {
                method: 'cash',
                totalAmount: {{ $remainingAmount }},
                cashReceived: {{ $remainingAmount }},

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
