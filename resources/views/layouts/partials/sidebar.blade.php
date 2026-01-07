{{-- Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-gray-900 transition-all duration-300 ease-in-out"
       :class="{
           'w-64': sidebarOpen,
           'w-20': !sidebarOpen,
           '-translate-x-full lg:translate-x-0': !sidebarMobileOpen,
           'translate-x-0': sidebarMobileOpen
       }">

    {{-- Logo Header --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white" x-show="sidebarOpen" x-transition>Restozen</span>
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">
            <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': !sidebarOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="sidebarMobileOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Outlet Selector --}}
    @if(Auth::user()->currentOutlet)
    <div class="px-3 py-4 border-b border-gray-800">
        <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-xl" x-show="sidebarOpen" x-transition>
            <div class="flex-shrink-0 w-10 h-10 bg-primary-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->currentOutlet->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->currentOutlet->company->name ?? '' }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Dashboard</span>
        </a>

        {{-- Menu Section --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</p>
        </div>

        <a href="{{ route('menu-categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('menu-categories.*') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Kategori</span>
        </a>

        <a href="{{ route('menu-items.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('menu-items.*') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Daftar Menu</span>
        </a>

        {{-- Table Section --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Meja</p>
        </div>

        <a href="{{ route('table-areas.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('table-areas.*') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Area Meja</span>
        </a>

        <a href="{{ route('tables.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('tables.*') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Denah Meja</span>
        </a>

        {{-- POS Section --}}
        <div class="pt-4" x-show="sidebarOpen">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">POS</p>
        </div>

        <a href="{{ route('orders.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('orders.create') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Kasir</span>
        </a>

        <a href="{{ route('orders.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') ? 'bg-primary-500/20 text-primary-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span x-show="sidebarOpen" x-transition class="font-medium">Riwayat Pesanan</span>
        </a>
    </nav>

    {{-- User Section at Bottom --}}
    <div class="p-3 border-t border-gray-800">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-800 transition-colors cursor-pointer"
             x-data="{ open: false }" @click="open = !open" @click.away="open = false">
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-full flex items-center justify-center">
                <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0" x-show="sidebarOpen" x-transition>
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->role ?? 'User' }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-400" x-show="sidebarOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
            </svg>

            {{-- Dropdown --}}
            <div x-show="open" x-transition
                 class="absolute bottom-full left-3 right-3 mb-2 bg-gray-800 rounded-xl shadow-xl border border-gray-700 py-1 z-50">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
