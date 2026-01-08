<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Top Selling</h1>
                <p class="text-sm text-gray-500 mt-1">Menu dan kategori terlaris</p>
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
        <form method="GET" action="{{ route('reports.top-selling') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
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
                    <a href="{{ route('reports.top-selling') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabs --}}
    <div class="mb-6" x-data="{ tab: 'items' }">
        <div class="border-b border-gray-200 print:hidden">
            <nav class="-mb-px flex gap-6">
                <button @click="tab = 'items'" 
                        :class="tab === 'items' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Top Menu Items
                </button>
                <button @click="tab = 'categories'" 
                        :class="tab === 'categories' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Top Categories
                </button>
            </nav>
        </div>

        {{-- Top Items Tab --}}
        <div x-show="tab === 'items'" class="mt-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Top 20 Menu Items Terlaris</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Menu</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Terjual</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($topItems as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($index < 3)
                                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm
                                                    {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-400') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-semibold text-gray-600 text-sm bg-gray-100">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->menu_item_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($item->total_quantity) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">pcs</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data penjualan menu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Categories Tab --}}
        <div x-show="tab === 'categories'" class="mt-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Top Categories Terlaris</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Terjual</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $totalCategoryRevenue = $topCategories->sum('total_revenue');
                            @endphp
                            @forelse($topCategories as $index => $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($index < 3)
                                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm
                                                    {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-400') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-semibold text-gray-600 text-sm bg-gray-100">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $category->category_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($category->total_quantity) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">items</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @php
                                            $contribution = $totalCategoryRevenue > 0 ? ($category->total_revenue / $totalCategoryRevenue) * 100 : 0;
                                        @endphp
                                        <span class="text-sm font-semibold text-primary-600">{{ number_format($contribution, 1) }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data penjualan kategori
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
