@php
    $currentPath = request()->path();
    $user = auth()->user();
    $outlet = $user->current_outlet;
    $userCapabilities = $outlet ? $user->capabilitiesAt($outlet) : [];
    
    // Define menu groups with required capability
    $menuGroups = [
        [
            'title' => 'Menu',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => 'dashboard',
                    'routes' => ['dashboard'],
                    'capability' => 'dashboard', // Everyone with outlet access has dashboard
                ],
                [
                    'name' => 'Kategori',
                    'path' => '/menu-categories',
                    'icon' => 'category',
                    'routes' => ['menu-categories.*'],
                    'capability' => 'menu_management',
                ],
                [
                    'name' => 'Daftar Menu',
                    'path' => '/menu-items',
                    'icon' => 'menu',
                    'routes' => ['menu-items.*'],
                    'capability' => 'menu_management',
                ],
            ]
        ],
        [
            'title' => 'Meja',
            'items' => [
                [
                    'name' => 'Area Meja',
                    'path' => '/table-areas',
                    'icon' => 'location',
                    'routes' => ['table-areas.*'],
                    'capability' => 'table_management',
                ],
                [
                    'name' => 'Denah Meja',
                    'path' => '/tables',
                    'icon' => 'table',
                    'routes' => ['tables.*'],
                    'capability' => 'table_management',
                ],
            ]
        ],
        [
            'title' => 'POS',
            'items' => [
                [
                    'name' => 'Buat Pesanan',
                    'path' => '/orders/create',
                    'icon' => 'order',
                    'routes' => ['orders.create', 'orders.select-menu', 'orders.select-menu.table'],
                    'capability' => 'waiter',
                ],
                [
                    'name' => 'Daftar Pesanan',
                    'path' => '/orders',
                    'icon' => 'history',
                    'routes' => ['orders.index', 'orders.show', 'payments.create', 'payments.store'],
                    'capability' => 'orders',
                ],
            ]
        ],
        [
            'title' => 'Management',
            'items' => [
                [
                    'name' => 'Kitchen Display',
                    'path' => '/kitchen',
                    'icon' => 'kitchen',
                    'routes' => ['kitchen.*'],
                    'capability' => 'kitchen',
                ],
                [
                    'name' => 'Karyawan',
                    'path' => '/employees',
                    'icon' => 'employees',
                    'routes' => ['employees.*'],
                    'capability' => 'employees',
                ],
                [
                    'name' => 'Laporan',
                    'path' => '/reports',
                    'icon' => 'report',
                    'routes' => ['reports.*'],
                    'capability' => 'reports',
                ],
            ]
        ],
    ];
    
    // Filter menu items based on user capabilities
    $filteredMenuGroups = [];
    foreach ($menuGroups as $group) {
        $filteredItems = array_filter($group['items'], function ($item) use ($userCapabilities) {
            return in_array($item['capability'], $userCapabilities);
        });
        
        if (!empty($filteredItems)) {
            $filteredMenuGroups[] = [
                'title' => $group['title'],
                'items' => array_values($filteredItems),
            ];
        }
    }
    
    // Icon SVGs
    $icons = [
        'dashboard' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        'category' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
        'menu' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
        'location' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'table' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>',
        'cashier' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        'order' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
        'history' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'kitchen' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>',
        'employees' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        'report' => '<svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
    ];
    
    function isRouteActive($routes) {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return true;
            }
        }
        return false;
    }
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">
    
    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
        <a href="{{ route('dashboard') }}">
            <!-- Full Logo -->
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="h-10 w-auto dark:brightness-0 dark:invert" 
                src="{{ asset('images/logo-premium-nobg.png') }}" 
                alt="RestoZen" />
            <!-- Icon Only -->
            <div x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                class="flex items-center justify-center w-10 h-10 rounded-lg bg-brand-500 text-white font-bold text-lg">
                R
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @foreach ($filteredMenuGroups as $groupIndex => $menuGroup)
                    <div>
                        <!-- Menu Group Title -->
                        <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400 font-medium"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'lg:justify-center' : 'justify-start'">
                            <template x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <svg class="size-6" viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z" fill="currentColor"/>
                                </svg>
                            </template>
                        </h2>

                        <!-- Menu Items -->
                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                @php
                                    $isActive = isRouteActive($item['routes']);
                                @endphp
                                <li>
                                    <a href="{{ $item['path'] }}" 
                                        class="menu-item group {{ $isActive ? 'menu-item-active' : 'menu-item-inactive' }}"
                                        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                                        
                                        <!-- Icon -->
                                        <span class="{{ $isActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                                            {!! $icons[$item['icon']] !!}
                                        </span>

                                        <!-- Text -->
                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="menu-item-text">
                                            {{ $item['name'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>

        <!-- User Capabilities Info (for debugging/transparency) -->
        <!-- <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" 
            x-transition 
            class="mb-4">
            @if($outlet)
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400">
                            {{ $user->displayRoleAt($outlet) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ count($userCapabilities) }} akses aktif
                    </p>
                </div>
            @endif
        </div> -->

        <!-- Sidebar Widget (Outlet Switcher) -->
        <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" 
            x-transition 
            class="mt-auto mb-6">
            @if(auth()->user()->outlets->count() > 1)
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-2 text-sm font-medium text-gray-800 dark:text-white/90">Outlet Aktif</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ session('current_outlet_name', 'Pilih Outlet') }}</p>
                    <a href="{{ route('dashboard') }}" 
                        class="flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Ganti Outlet
                    </a>
                </div>
            @endif
        </div>
    </div>
</aside>
