<div class="rounded-xl border border-gray-200 bg-white overflow-hidden flex flex-col h-full transition-all duration-200 hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Card Header -->
    <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-start {{ $order->status === 'preparing' ? 'bg-warning-50 dark:bg-warning-500/10' : 'bg-gray-50 dark:bg-gray-900' }} dark:border-gray-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 tracking-wider">Meja</span>
                <span class="text-xl font-bold text-gray-800 dark:text-white/90">{{ $order->table->name ?? 'N/A' }}</span>
            </div>
            <div class="text-xs text-gray-400 font-mono mt-1">#{{ $order->order_number }}</div>
        </div>
        <div class="text-right">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block">Durasi</span>
            <span class="text-lg font-bold font-mono text-gray-700 dark:text-gray-300" id="timer-{{ $order->id }}">
                {{ $order->confirmed_at ? $order->confirmed_at->diffForHumans(null, true, true) : '0m' }}
            </span>
        </div>
    </div>

    <!-- Scrollable Items Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3">
        @foreach($order->items as $item)
            <div class="flex items-start justify-between group">
                <div class="flex-1">
                    <div class="flex items-baseline gap-2">
                        <span class="font-bold text-lg {{ $item->status === 'ready' || $item->status === 'served' ? 'text-success-600 dark:text-success-400 line-through opacity-60' : 'text-gray-800 dark:text-white/90' }}">
                            {{ $item->quantity }}x
                        </span>
                        <span class="font-medium {{ $item->status === 'ready' || $item->status === 'served' ? 'text-gray-400 line-through' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $item->menuItem->name }}
                        </span>
                    </div>
                    @if($item->notes)
                        <div class="text-xs text-error-600 dark:text-error-400 italic mt-1 bg-error-50 dark:bg-error-500/10 px-2 py-1 rounded inline-block">
                            Catatan: {{ $item->notes }}
                        </div>
                    @endif
                </div>

                <!-- Item Actions -->
                @if(!in_array($item->status, ['ready', 'served', 'cancelled']))
                    <form action="{{ route('kitchen.update-item', $item) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        @if($item->status === 'pending')
                             <button type="submit" name="status" value="preparing" 
                                class="p-2 rounded-full bg-gray-100 hover:bg-warning-100 text-gray-400 hover:text-warning-600 transition-colors dark:bg-gray-800 dark:hover:bg-warning-500/20 dark:hover:text-warning-400" 
                                title="Mulai Siapkan">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        @elseif($item->status === 'preparing')
                            <button type="submit" name="status" value="ready" 
                                class="p-2 rounded-full bg-warning-100 hover:bg-success-100 text-warning-600 hover:text-success-600 transition-colors animate-pulse dark:bg-warning-500/20 dark:hover:bg-success-500/20 dark:text-warning-400 dark:hover:text-success-400" 
                                title="Tandai Siap">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        @endif
                    </form>
                @else
                    <div class="p-2">
                        <svg class="size-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Actions Footer -->
    <div class="p-4 bg-gray-50 border-t border-gray-200 dark:bg-gray-900 dark:border-gray-800">
        <form action="{{ route('kitchen.mark-ready', $order) }}" method="POST">
            @csrf
            <button type="submit" 
                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-success-600 hover:bg-success-700 text-white rounded-lg font-medium transition-colors">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tandai Semua Siap
            </button>
        </form>
    </div>
</div>

<script>
    // Simple client-side timer update to increment the minutes
    (function() {
        const timerEl = document.getElementById('timer-{{ $order->id }}');
        const confirmedAt = new Date('{{ $order->confirmed_at }}');

        function updateCardTimer() {
            const now = new Date();
            const diffMs = now - confirmedAt;
            const diffMins = Math.floor(diffMs / 60000);
            
            // Format friendly like "5m", "1h 2m"
            let text = diffMins + 'm';
            if (diffMins >= 60) {
                 const hours = Math.floor(diffMins / 60);
                 const mins = diffMins % 60;
                 text = `${hours}h ${mins}m`;
            }
            timerEl.textContent = text;
        }

        // Run every minute
        setInterval(updateCardTimer, 60000);
    })();
</script>
