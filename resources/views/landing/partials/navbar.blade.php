{{-- Landing Page Navbar --}}
<div x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-9999">
    {{-- Announcement Banner --}}
    <div class="w-full py-2.5 font-medium text-theme-sm text-white text-center bg-gradient-to-r from-brand-600 via-brand-500 to-success-500">
        <p class="flex items-center justify-center gap-2 flex-wrap">
            <span class="px-3 py-1 rounded-full text-brand-600 bg-white font-semibold text-theme-xs">PROMO</span>
            <span>Dapatkan diskon 20% untuk 3 bulan pertama!</span>
        </p>
    </div>

    {{-- Main Navbar --}}
    <nav class="flex items-center justify-between w-full py-4 px-6 md:px-16 lg:px-24 xl:px-32 backdrop-blur-md bg-white/95 dark:bg-gray-900/95 text-gray-700 dark:text-gray-300 text-theme-sm border-b border-gray-200 dark:border-gray-800 shadow-theme-xs">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('images/marupos-logo.png') }}" alt="MARUPOS Logo" class="h-12 w-auto dark:hidden">
            <img src="{{ asset('images/marupos-logo-white.png') }}" alt="MARUPOS Logo" class="h-12 w-auto hidden dark:block">
        </a>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex items-center gap-8 flex-1 justify-center transition duration-500">
            <a href="#features" class="hover:text-brand-500 transition font-medium">Fitur</a>
            <a href="#how-it-works" class="hover:text-brand-500 transition font-medium">Cara Kerja</a>
            <a href="#testimonials" class="hover:text-brand-500 transition font-medium">Testimoni</a>
            <a href="#pricing" class="hover:text-brand-500 transition font-medium">Harga</a>
            <a href="#faq" class="hover:text-brand-500 transition font-medium">FAQ</a>
        </div>

        {{-- Auth Buttons --}}
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}" class="px-5 py-2.5 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-theme-xs">
                Masuk
            </a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 transition text-white rounded-lg font-medium shadow-theme-xs">
                    Daftar Sekarang
                </a>
            @endif
        </div>

        {{-- Mobile Menu Button --}}
        <button @click="mobileMenuOpen = true" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
        </button>

        {{-- Mobile Navigation Overlay --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 z-9999 bg-black/50 backdrop-blur-sm md:hidden">
        </div>

        {{-- Mobile Navigation Panel --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed top-0 right-0 h-full w-72 z-99999 bg-white dark:bg-gray-900 shadow-theme-xl md:hidden">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                    <img src="{{ asset('images/marupos-logo.png') }}" alt="MARUPOS Logo" class="h-8 w-auto dark:hidden">
                    <img src="{{ asset('images/marupos-logo-white.png') }}" alt="MARUPOS Logo" class="h-8 w-auto hidden dark:block">
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <nav class="flex-1 p-4 space-y-2">
                    <a @click="mobileMenuOpen = false" href="#features" class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition">Fitur</a>
                    <a @click="mobileMenuOpen = false" href="#how-it-works" class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition">Cara Kerja</a>
                    <a @click="mobileMenuOpen = false" href="#testimonials" class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition">Testimoni</a>
                    <a @click="mobileMenuOpen = false" href="#pricing" class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition">Harga</a>
                    <a @click="mobileMenuOpen = false" href="#faq" class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 font-medium transition">FAQ</a>
                </nav>
                <div class="p-4 space-y-3 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-brand-500 hover:bg-brand-600 text-white rounded-lg font-medium transition">
                            Daftar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</div>
