<x-app-layout>
    <x-slot name="title">Laporan Keuangan</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Laporan Keuangan</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Analisis performa keuangan restoran Anda</p>
        </div>
        <x-ui.button href="{{ route('reports.sales') }}" variant="primary">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Lihat Detail
        </x-ui.button>
    </div>

    <!-- Date Filter -->
    <div class="mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                        class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" variant="primary" class="flex-1">
                        Terapkan
                    </x-ui.button>
                    <x-ui.button href="{{ route('reports.index') }}" variant="outline">
                        Reset
                    </x-ui.button>
                </div>
                <div class="flex items-end">
                    <div class="w-full flex gap-2">
                        <a href="{{ route('reports.index', ['start_date' => today(), 'end_date' => today()]) }}" 
                            class="flex-1 text-center px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300">
                            Hari Ini
                        </a>
                        <a href="{{ route('reports.index', ['start_date' => today()->startOfMonth(), 'end_date' => today()]) }}" 
                            class="flex-1 text-center px-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300">
                            Bulan Ini
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10">
                    <svg class="size-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white/90">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10">
                    <svg class="size-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($totalOrders) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-500/10">
                    <svg class="size-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pesanan Selesai</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($completedOrders) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-warning-50 dark:bg-warning-500/10">
                    <svg class="size-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rata-rata Order</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white/90">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Breakdown -->
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-800">
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Metode Pembayaran</h3>
                <a href="{{ route('reports.payment-methods') }}" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">Lihat Detail</a>
            </div>
            <div class="p-6">
                @if($paymentBreakdown->count() > 0)
                    <div class="space-y-3">
                        @foreach($paymentBreakdown as $payment)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                                        {{ str_replace('_', ' ', $payment->payment_method) }}
                                    </span>
                                </div>
                                <span class="text-sm font-bold text-gray-800 dark:text-white/90">Rp {{ number_format($payment->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada data pembayaran</p>
                @endif
            </div>
        </div>

        <!-- Revenue Trend (Last 7 Days) -->
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-800">
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Trend Revenue (7 Hari Terakhir)</h3>
                <a href="{{ route('reports.sales') }}" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">Lihat Semua</a>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($revenueTrend as $trend)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $trend['date'] }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                                    @php
                                        $maxRevenue = collect($revenueTrend)->max('revenue');
                                        $percentage = $maxRevenue > 0 ? ($trend['revenue'] / $maxRevenue) * 100 : 0;
                                    @endphp
                                    <div class="bg-brand-500 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white/90 w-28 text-right">
                                    Rp {{ number_format($trend['revenue'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('reports.sales') }}" 
            class="rounded-xl border border-gray-200 bg-white p-6 transition-all hover:border-brand-300 hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-brand-700 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-50 dark:bg-brand-500/10 rounded-xl flex items-center justify-center group-hover:bg-brand-100 dark:group-hover:bg-brand-500/20 transition-colors">
                    <svg class="size-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Laporan Penjualan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Detail transaksi penjualan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.payment-methods') }}" 
            class="rounded-xl border border-gray-200 bg-white p-6 transition-all hover:border-success-300 hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-success-700 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-success-50 dark:bg-success-500/10 rounded-xl flex items-center justify-center group-hover:bg-success-100 dark:group-hover:bg-success-500/20 transition-colors">
                    <svg class="size-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Metode Pembayaran</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Breakdown per metode</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.top-selling') }}" 
            class="rounded-xl border border-gray-200 bg-white p-6 transition-all hover:border-purple-300 hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-purple-700 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center group-hover:bg-purple-100 dark:group-hover:bg-purple-500/20 transition-colors">
                    <svg class="size-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Top Selling</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Menu terlaris</p>
                </div>
            </div>
        </a>
    </div>
</x-app-layout>
