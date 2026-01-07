<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.show', $order) }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran</h1>
                    <p class="text-sm text-gray-500">{{ $order->order_number }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Order Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Ringkasan Pesanan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="px-6 py-3 flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->menu_item_name }} ×{{ $item->quantity }}</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($paidAmount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Sudah Dibayar</span>
                                <span class="text-green-600">-Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Sisa Tagihan</span>
                            <span class="text-primary-600">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="paymentForm()">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Metode Pembayaran</h3>
                </div>
                <form action="{{ route('payments.store', $order) }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    {{-- Payment Method --}}
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                <svg class="w-8 h-8 text-gray-400 peer-checked:text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Cash</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="transfer" x-model="method" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                <svg class="w-8 h-8 text-gray-400 peer-checked:text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Transfer</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="qris" x-model="method" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                <svg class="w-8 h-8 text-gray-400 peer-checked:text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">QRIS</span>
                            </div>
                        </label>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number"
                                   name="amount"
                                   x-model="amount"
                                   value="{{ $remainingAmount }}"
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 text-lg font-semibold border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button type="button" @click="amount = {{ $remainingAmount }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">Pas</button>
                            <button type="button" @click="amount = Math.ceil({{ $remainingAmount }} / 50000) * 50000" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">50rb</button>
                            <button type="button" @click="amount = Math.ceil({{ $remainingAmount }} / 100000) * 100000" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">100rb</button>
                        </div>
                    </div>

                    {{-- Cash Received (for cash payments) --}}
                    <div x-show="method === 'cash'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number"
                                   name="cash_received"
                                   x-model="cashReceived"
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 text-lg font-semibold border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button type="button" @click="cashReceived = amount" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">Pas</button>
                            <button type="button" @click="cashReceived = 50000" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">50rb</button>
                            <button type="button" @click="cashReceived = 100000" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">100rb</button>
                            <button type="button" @click="cashReceived = 200000" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">200rb</button>
                        </div>

                        {{-- Change --}}
                        <div x-show="cashReceived >= amount" class="mt-4 p-4 bg-secondary-50 rounded-xl" x-transition>
                            <div class="flex justify-between items-center">
                                <span class="text-secondary-700 font-medium">Kembalian</span>
                                <span class="text-2xl font-bold text-secondary-600" x-text="'Rp ' + formatNumber(cashReceived - amount)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Reference Number (for transfer/qris) --}}
                    <div x-show="method === 'transfer' || method === 'qris'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Referensi</label>
                        <input type="text"
                               name="reference_number"
                               placeholder="Masukkan nomor referensi"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            :disabled="!method || amount <= 0"
                            :class="!method || amount <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700'"
                            class="w-full py-4 rounded-xl text-white font-bold text-lg transition-colors">
                        Proses Pembayaran
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
                amount: {{ $remainingAmount }},
                cashReceived: {{ $remainingAmount }},

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
