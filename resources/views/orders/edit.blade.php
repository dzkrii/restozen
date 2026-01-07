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
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Item - {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500">
                        @if($order->table)
                            Meja {{ $order->table->number }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="editOrder()" class="flex gap-6">
        {{-- Left: Menu Items --}}
        <div class="flex-1">
            {{-- Category Tabs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
                <div class="flex overflow-x-auto scrollbar-hide">
                    <button @click="selectedCategory = null"
                            :class="selectedCategory === null ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-shrink-0 px-5 py-3 font-medium text-sm transition-colors">
                        Semua
                    </button>
                    @foreach($categories as $category)
                        <button @click="selectedCategory = {{ $category->id }}"
                                :class="selectedCategory === {{ $category->id }} ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                                class="flex-shrink-0 px-5 py-3 font-medium text-sm transition-colors border-l border-gray-100">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Menu Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    @foreach($category->menuItems as $item)
                        <div x-show="selectedCategory === null || selectedCategory === {{ $category->id }}"
                             x-transition
                             @click="addToCart({
                                 id: {{ $item->id }},
                                 name: '{{ addslashes($item->name) }}',
                                 price: {{ $item->price }}
                             })"
                             class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md hover:border-primary-200 transition-all group">
                            @if($item->image)
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-3">
                                <h4 class="font-medium text-gray-900 text-sm line-clamp-2 mb-1">{{ $item->name }}</h4>
                                <p class="text-primary-600 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- Right: Cart --}}
        <div class="w-96 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
                {{-- Current Order Items --}}
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Item Saat Ini ({{ $order->items->count() }})</h3>
                </div>
                <div class="max-h-[200px] overflow-y-auto divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <div class="px-5 py-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-700">{{ $item->menu_item_name }}</span>
                                <span class="text-gray-500">×{{ $item->quantity }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- New Items --}}
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between bg-primary-50">
                    <h3 class="font-semibold text-primary-700">Item Baru</h3>
                    <button @click="clearCart()" x-show="cart.length > 0" class="text-sm text-red-500 hover:text-red-600">
                        Hapus
                    </button>
                </div>
                <div class="max-h-[200px] overflow-y-auto">
                    <template x-if="cart.length === 0">
                        <div class="p-6 text-center">
                            <p class="text-gray-400 text-sm">Klik menu untuk menambahkan</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="px-5 py-3 border-b border-gray-50 hover:bg-gray-50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 text-sm truncate" x-text="item.name"></h4>
                                    <p class="text-primary-600 text-sm font-semibold" x-text="'Rp ' + formatNumber(item.price)"></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="decreaseQty(index)" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="w-8 text-center font-semibold text-gray-900" x-text="item.qty"></span>
                                    <button @click="increaseQty(index)" class="w-7 h-7 rounded-lg bg-primary-100 hover:bg-primary-200 flex items-center justify-center text-primary-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <input type="text"
                                   x-model="item.notes"
                                   placeholder="Catatan (opsional)"
                                   class="mt-2 text-xs border-0 bg-gray-50 rounded px-2 py-1 w-full focus:ring-1 focus:ring-primary-500">
                        </div>
                    </template>
                </div>

                {{-- Submit --}}
                <div class="p-5 border-t border-gray-100">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500">Total Item Baru</span>
                        <span class="font-semibold text-gray-900" x-text="'Rp ' + formatNumber(subtotal)"></span>
                    </div>

                    <button @click="submitItems()"
                            :disabled="cart.length === 0 || isSubmitting"
                            :class="cart.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700'"
                            class="w-full py-3 rounded-xl text-white font-semibold transition-colors flex items-center justify-center gap-2">
                        <svg x-show="isSubmitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Memproses...' : 'Tambah ' + cart.length + ' Item'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editOrder() {
            return {
                cart: [],
                selectedCategory: null,
                isSubmitting: false,

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
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
                    this.cart = [];
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                async submitItems() {
                    if (this.cart.length === 0) return;

                    this.isSubmitting = true;

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PUT');

                    this.cart.forEach((item, i) => {
                        formData.append(`items[${i}][menu_item_id]`, item.id);
                        formData.append(`items[${i}][quantity]`, item.qty);
                        formData.append(`items[${i}][notes]`, item.notes || '');
                    });

                    try {
                        const response = await fetch('{{ route('orders.update', $order) }}', {
                            method: 'POST',
                            body: formData
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            window.location.href = '{{ route('orders.show', $order) }}';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
