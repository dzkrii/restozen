<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
            <a href="{{ route('reports.sales') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Lihat Detail
            </a>
        </div>
    </x-slot>

    {{-- Date Filter --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
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
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Terapkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
                <div class="flex items-end">
                    <div class="w-full flex gap-2">
                        <a href="{{ route('reports.index', ['start_date' => today(), 'end_date' => today()]) }}" 
                           class="flex-1 text-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition-colors">
                            Hari Ini
                        </a>
                        <a href="{{ route('reports.index', ['start_date' => today()->startOfMonth(), 'end_date' => today()]) }}" 
                           class="flex-1 text-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition-colors">
                            Bulan Ini
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-secondary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-accent-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pesanan Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($completedOrders) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-warning-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Rata-rata Order</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Payment Methods Breakdown --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Metode Pembayaran</h3>
                <a href="{{ route('reports.payment-methods') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Detail</a>
            </div>
            <div class="p-6">
                @if($paymentBreakdown->count() > 0)
                    <div class="space-y-3">
                        @foreach($paymentBreakdown as $payment)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                                    <span class="text-sm font-medium text-gray-700 capitalize">
                                        {{ str_replace('_', ' ', $payment->payment_method) }}
                                    </span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada data pembayaran</p>
                @endif
            </div>
        </div>

        {{-- Revenue Trend (Last 7 Days) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Trend Revenue (7 Hari Terakhir)</h3>
                <a href="{{ route('reports.sales') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($revenueTrend as $trend)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $trend['date'] }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-100 rounded-full h-2 overflow-hidden">
                                    @php
                                        $maxRevenue = collect($revenueTrend)->max('revenue');
                                        $percentage = $maxRevenue > 0 ? ($trend['revenue'] / $maxRevenue) * 100 : 0;
                                    @endphp
                                    <div class="bg-primary-500 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-28 text-right">
                                    Rp {{ number_format($trend['revenue'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('reports.sales') }}" 
           class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-primary-300 hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Laporan Penjualan</h4>
                    <p class="text-sm text-gray-500">Detail transaksi penjualan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.payment-methods') }}" 
           class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-secondary-300 hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-secondary-50 rounded-xl flex items-center justify-center group-hover:bg-secondary-100 transition-colors">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Metode Pembayaran</h4>
                    <p class="text-sm text-gray-500">Breakdown per metode</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.top-selling') }}" 
           class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-accent-300 hover:shadow-md transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-accent-50 rounded-xl flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00 2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Top Selling</h4>
                    <p class="text-sm text-gray-500">Menu terlaris</p>
                </div>
            </div>
        </a>
    </div>
</x-app-layout>
