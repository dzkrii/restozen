<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Dashboard</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ringkasan performa restoran Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <x-ui.button href="{{ route('orders.create') }}" variant="primary">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Pesanan Baru
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6">
            <!-- Metrics -->
            <x-dashboard.metrics :stats="$stats" />

            <!-- Monthly Sales Chart -->
            <x-dashboard.monthly-sale :stats="$stats" />
        </div>
    </div>
</x-app-layout>
