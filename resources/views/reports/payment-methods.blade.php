<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Metode Pembayaran</h1>
                <p class="text-sm text-gray-500 mt-1">Breakdown revenue per metode pembayaran</p>
            </div>
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors print:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print / Save PDF
            </button>
        </div>
    </x-slot>

    {{-- Date Filter --}}
    <div class="mb-6 print:hidden">
        <form method="GET" action="{{ route('reports.payment-methods') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div class="flex items-end gap-2 col-span-2">
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Terapkan
                    </button>
                    <a href="{{ route('reports.payment-methods') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl p-6 text-white">
            <p class="text-secondary-100 text-sm mb-1">Total Revenue</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-6 text-white">
            <p class="text-primary-100 text-sm mb-1">Total Transaksi</p>
            <p class="text-3xl font-bold">{{ number_format($totalTransactions) }}</p>
        </div>
    </div>

    {{-- Payment Methods Breakdown --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Breakdown Per Metode Pembayaran</h3>
        </div>
        <div class="p-6">
            @if($paymentData->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Table --}}
                    <div class="space-y-3">
                        @php
                            $paymentIcons = [
                                'cash' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                'card' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                'qris' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>',
                                'transfer' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>',
                                'ewallet' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                            ];
                        @endphp

                        @foreach($paymentData as $payment)
                            <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $paymentIcons[$payment->payment_method] ?? $paymentIcons['cash'] !!}
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</h4>
                                        <p class="text-sm text-gray-500">{{ number_format($payment->transaction_count) }} transaksi</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $totalRevenue > 0 ? number_format(($payment->total_amount / $totalRevenue) * 100, 1) : 0 }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Visual Chart (Progress Bars) --}}
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Visualisasi Persentase</h4>
                        @foreach($paymentData as $payment)
                            @php
                                $percentage = $totalRevenue > 0 ? ($payment->total_amount / $totalRevenue) * 100 : 0;
                                $colors = [
                                    'cash' => 'bg-secondary-500',
                                    'card' => 'bg-primary-500',
                                    'qris' => 'bg-accent-500',
                                    'transfer' => 'bg-warning-500',
                                    'ewallet' => 'bg-purple-500',
                                ];
                                $color = $colors[$payment->payment_method] ?? 'bg-gray-500';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($percentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="{{ $color }} h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada data pembayaran untuk periode ini</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
