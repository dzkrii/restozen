<x-app-layout>
    <x-slot name="title">Pilih Menu</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ $table ? route('orders.create', ['type' => 'dine_in']) : route('orders.create') }}"
               class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Pilih Menu</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    @if($table)
                        Meja {{ $table->number }} • {{ ucfirst($orderType) }}
                    @else
                        {{ $orderType === 'takeaway' ? 'Takeaway' : ($orderType === 'delivery' ? 'Delivery' : 'Order') }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div x-data="posMenu()" class="flex flex-col lg:flex-row gap-6">
        {{-- Left: Menu Items --}}
        <div class="flex-1 min-w-0">
            {{-- Search Bar --}}
            <div class="mb-4">
                <div class="relative">
                    <input
                        type="text"
                        x-model="searchQuery"
                        placeholder="Cari menu..."
                        class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500"
                    >
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Category Tabs --}}
            <div class="mb-4 rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
                <div class="flex overflow-x-auto scrollbar-hide">
                    <button @click="selectedCategory = null"
                            :class="selectedCategory === null
                                ? 'bg-brand-500 text-white'
                                : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                            class="flex-shrink-0 px-5 py-3 font-medium text-sm transition-colors">
                        Semua
                    </button>
                    @foreach($categories as $category)
                        <button @click="selectedCategory = {{ $category->id }}"
                                :class="selectedCategory === {{ $category->id }}
                                    ? 'bg-brand-500 text-white'
                                    : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800'"
                                class="flex-shrink-0 px-5 py-3 font-medium text-sm transition-colors border-l border-gray-100 dark:border-gray-700">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Menu Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    @foreach($category->menuItems as $item)
                        <div x-show="(selectedCategory === null || selectedCategory === {{ $category->id }}) && matchesSearch('{{ addslashes($item->name) }}')"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             @click="addToCart({
                                 id: {{ $item->id }},
                                 name: '{{ addslashes($item->name) }}',
                                 price: {{ $item->price }},
                                 image: '{{ $item->image ? asset('storage/' . $item->image) : '' }}'
                             })"
                             class="group cursor-pointer rounded-xl border border-gray-200 bg-white transition-all hover:shadow-lg hover:border-brand-300 dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-brand-700 overflow-hidden">
                            @if($item->image)
                                <div class="aspect-square bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                    {{-- Add to cart indicator --}}
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-500/90 text-white shadow-lg backdrop-blur-sm">
                                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 flex items-center justify-center relative">
                                    <svg class="size-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{-- Add to cart indicator --}}
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-500/90 text-white shadow-lg backdrop-blur-sm">
                                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-medium text-gray-800 dark:text-white/90 text-sm line-clamp-2 mb-1.5">{{ $item->name }}</h4>
                                <p class="text-brand-600 dark:text-brand-400 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            @if($categories->isEmpty() || $categories->sum(fn($c) => $c->menuItems->count()) === 0)
                <div class="rounded-xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                        <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Belum ada menu</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan menu terlebih dahulu</p>
                    <a href="{{ route('menu-items.create') }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-600">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Menu
                    </a>
                </div>
            @endif
        </div>

        {{-- Right: Cart --}}
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] sticky top-6 overflow-hidden">
                {{-- Cart Header --}}
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500/10 dark:bg-brand-500/20">
                            <svg class="size-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-white/90">Keranjang</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="cart.length + ' item'"></p>
                        </div>
                    </div>
                    <button @click="clearCart()" x-show="cart.length > 0"
                            class="text-sm font-medium text-error-500 hover:text-error-600 dark:text-error-400 dark:hover:text-error-300 transition-colors">
                        Hapus Semua
                    </button>
                </div>

                {{-- Cart Items --}}
                <div class="max-h-[350px] overflow-y-auto">
                    <template x-if="cart.length === 0">
                        <div class="p-8 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-3">
                                <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Keranjang kosong</p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Klik menu untuk menambahkan</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-800 dark:text-white/90 text-sm truncate" x-text="item.name"></h4>
                                    <p class="text-brand-600 dark:text-brand-400 text-sm font-semibold mt-0.5" x-text="'Rp ' + formatNumber(item.price)"></p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button @click="decreaseQty(index)"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="w-8 text-center font-semibold text-gray-800 dark:text-white/90" x-text="item.qty"></span>
                                    <button @click="increaseQty(index)"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 text-white transition-colors hover:bg-brand-600">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            {{-- Item subtotal --}}
                            <div class="flex items-center justify-between mt-3 gap-3">
                                <input type="text"
                                       x-model="item.notes"
                                       placeholder="Catatan (opsional)"
                                       class="flex-1 text-xs border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:placeholder:text-gray-500">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap" x-text="'Rp ' + formatNumber(item.price * item.qty)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Cart Footer --}}
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 space-y-4 bg-gray-50 dark:bg-gray-800/30">
                    {{-- Customer Info (optional) --}}
                    <div class="space-y-3">
                        <input type="text"
                               x-model="customerName"
                               placeholder="Nama Pelanggan (opsional)"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
                        <input type="text"
                               x-model="customerPhone"
                               placeholder="No. HP (opsional)"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
                        <textarea x-model="notes"
                                  placeholder="Catatan pesanan..."
                                  rows="2"
                                  class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500"></textarea>
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-2 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300" x-text="'Rp ' + formatNumber(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-800 dark:text-white/90">Total</span>
                            <span class="text-brand-600 dark:text-brand-400" x-text="'Rp ' + formatNumber(subtotal)"></span>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <form :action="'{{ route('orders.store') }}'" method="POST" @submit.prevent="submitOrder">
                        @csrf
                        <input type="hidden" name="table_id" value="{{ $table?->id }}">
                        <input type="hidden" name="order_type" value="{{ $orderType }}">
                        <input type="hidden" name="customer_name" :value="customerName">
                        <input type="hidden" name="customer_phone" :value="customerPhone">
                        <input type="hidden" name="notes" :value="notes">
                        <input type="hidden" name="items" :value="JSON.stringify(cartForSubmit)">

                        <button type="submit"
                                :disabled="cart.length === 0 || isSubmitting"
                                :class="cart.length === 0 ? 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed' : 'bg-brand-500 hover:bg-brand-600 shadow-lg shadow-brand-500/25'"
                                class="w-full py-3.5 rounded-xl text-white font-semibold transition-all flex items-center justify-center gap-2">
                            <svg x-show="isSubmitting" class="animate-spin size-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!isSubmitting" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span x-text="isSubmitting ? 'Memproses...' : 'Buat Pesanan (' + cart.length + ' item)'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function posMenu() {
            return {
                cart: [],
                selectedCategory: null,
                customerName: '',
                customerPhone: '',
                notes: '',
                searchQuery: '',
                isSubmitting: false,

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get cartForSubmit() {
                    return this.cart.map(item => ({
                        menu_item_id: item.id,
                        quantity: item.qty,
                        notes: item.notes || ''
                    }));
                },

                matchesSearch(name) {
                    if (!this.searchQuery) return true;
                    return name.toLowerCase().includes(this.searchQuery.toLowerCase());
                },

                addToCart(item) {
                    const existing = this.cart.find(i => i.id === item.id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({ ...item, qty: 1, notes: '' });
                    }
                },

                increaseQty(index) {
                    this.cart[index].qty++;
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.cart.splice(index, 1);
                    }
                },

                clearCart() {
                    if (confirm('Hapus semua item dari keranjang?')) {
                        this.cart = [];
                    }
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                async submitOrder() {
                    if (this.cart.length === 0) return;

                    this.isSubmitting = true;

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('table_id', '{{ $table?->id ?? '' }}');
                    formData.append('order_type', '{{ $orderType }}');
                    formData.append('customer_name', this.customerName);
                    formData.append('customer_phone', this.customerPhone);
                    formData.append('notes', this.notes);

                    this.cart.forEach((item, i) => {
                        formData.append(`items[${i}][menu_item_id]`, item.id);
                        formData.append(`items[${i}][quantity]`, item.qty);
                        formData.append(`items[${i}][notes]`, item.notes || '');
                    });

                    try {
                        const response = await fetch('{{ route('orders.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                            }
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            window.location.href = '{{ route('orders.index') }}';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat membuat pesanan');
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
