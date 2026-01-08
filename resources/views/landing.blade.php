<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RestoZen - Modern POS untuk Restoran Anda</title>
    <meta name="description" content="RestoZen adalah sistem POS modern untuk restoran dengan fitur lengkap: manajemen menu, QR order, kitchen display, dan laporan keuangan.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white font-sans antialiased">

    <!-- Announcement Banner -->
    <div class="w-full py-2.5 font-medium text-sm text-white text-center bg-gradient-to-r from-primary-600 via-primary-500 to-accent-400">
        <p><span class="px-3 py-1 rounded-md text-primary-600 bg-white mr-2 font-semibold">PROMO</span>Coba RestoZen hari ini dan dapatkan diskon 20% untuk 3 bulan pertama!</p>
    </div>

    <!-- Navbar (updated to match new hero style) -->
    <nav class="sticky top-0 z-[999] flex items-center w-full py-4 px-6 md:px-16 lg:px-24 xl:px-32 backdrop-blur-md bg-white/80 text-neutral-800 text-sm border-b border-neutral-100">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-premium-nobg.png') }}" alt="RestoZen Logo" class="h-8 w-auto">
        </a>

        <!-- Menu always centered -->
        <div class="hidden md:flex items-center gap-8 flex-1 justify-center transition duration-500">
            <a href="#features" class="hover:text-primary-500 transition">Fitur</a>
            <a href="#pricing" class="hover:text-primary-500 transition">Harga</a>
            <a href="#contact" class="hover:text-primary-500 transition">Kontak</a>
        </div>

        <!-- Auth buttons always visible -->
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}" class="hover:bg-neutral-100 transition px-6 py-2 border border-primary-500 text-primary-600 rounded-lg font-medium">
                Masuk
            </a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-6 py-2 bg-primary-500 hover:bg-primary-600 transition text-white rounded-lg font-medium">
                    Daftar
                </a>
            @endif
        </div>

        <!-- Mobile Menu Button -->
        <button id="open-menu" class="md:hidden active:scale-90 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
        </button>
    </nav>

    <!-- Mobile Navigation -->
    <div id="mobile-navLinks" class="fixed inset-0 z-[100] bg-white/95 text-neutral-800 backdrop-blur flex flex-col items-center justify-center text-lg gap-8 md:hidden transition-transform duration-300 -translate-x-full">
        <a href="#features" onclick="closeMenuHandler()">Fitur</a>
        <a href="#pricing" onclick="closeMenuHandler()">Harga</a>
        <a href="#contact" onclick="closeMenuHandler()">Kontak</a>
        <a href="{{ route('login') }}" class="px-6 py-2 border border-primary-500 text-primary-600 rounded-lg">Masuk</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="px-6 py-2 bg-primary-500 text-white rounded-lg">Daftar</a>
        @endif
        <button id="close-menu" class="active:ring-3 active:ring-primary-200 aspect-square size-10 p-1 items-center justify-center bg-neutral-100 hover:bg-neutral-200 transition text-black rounded-md flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>

    <!-- Hero Section -->
    <section class="flex flex-col items-center text-sm bg-[url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2260%22%20height%3D%2260%22%3E%3Cpath%20d%3D%22M0%200h60v60H0z%22%20fill%3D%22none%22%2F%3E%3Cpath%20d%3D%22M60%200v60M0%200h60%22%20stroke%3D%22%23f1f1f1%22%20stroke-width%3D%221%22%2F%3E%3C%2Fsvg%3E')] bg-repeat">
        <!-- Hero Content -->
        <main class="flex flex-col items-center max-md:px-4 pb-20">
            <!-- Badge -->
            <a href="{{ route('register') }}" class="mt-24 flex items-center gap-2 border border-primary-200 rounded-full p-1 pr-4 text-sm font-medium text-primary-600 bg-primary-50/50 hover:bg-primary-100/50 transition" data-aos="fade-up" data-aos-duration="800">
                <span class="bg-primary-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                    BARU
                </span>
                <p class="flex items-center gap-1">
                    <span>Coba gratis 14 hari tanpa kartu kredit</span>
                    <svg class="mt-0.5" width="6" height="9" viewBox="0 0 6 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="m1 1 4 3.5L1 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </p>
            </a>

            <!-- Headline -->
            <h1 class="text-center text-4xl leading-tight md:text-5xl lg:text-6xl md:leading-[1.2] font-bold max-w-4xl text-neutral-900 mt-8" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                Kelola Restoran Anda dengan <span class="text-primary-500">Lebih Mudah</span> dan Efisien
            </h1>

            <!-- Subtitle -->
            <p class="text-center text-base md:text-lg text-neutral-600 max-w-2xl mt-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                RestoZen membantu Anda mengelola pesanan, menu, meja, dapur, dan laporan keuangan dalam satu platform terintegrasi.
            </p>

            <!-- CTA Buttons -->
            <div class="flex items-center gap-4 mt-10" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <a href="{{ route('register') }}" class="flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white active:scale-95 rounded-xl px-7 h-12 font-semibold transition shadow-lg shadow-primary-500/25">
                    Mulai Gratis
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.166 10h11.667m0 0L9.999 4.165m5.834 5.833-5.834 5.834" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="#features" class="border border-neutral-300 active:scale-95 hover:bg-neutral-50 transition text-neutral-700 rounded-xl px-7 h-12 font-semibold flex items-center">
                    Lihat Fitur
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-16" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-neutral-900">500+</div>
                    <div class="text-neutral-500 text-sm mt-1">Restoran Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-neutral-900">50K+</div>
                    <div class="text-neutral-500 text-sm mt-1">Transaksi/Bulan</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-neutral-900">99.9%</div>
                    <div class="text-neutral-500 text-sm mt-1">Uptime</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-neutral-900">24/7</div>
                    <div class="text-neutral-500 text-sm mt-1">Support</div>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-16 w-full max-w-5xl px-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
                <div class="bg-gradient-to-b from-neutral-100 to-white p-2 md:p-4 rounded-2xl shadow-2xl shadow-neutral-300/50 border border-neutral-200">
                    <div class="bg-neutral-900 rounded-xl overflow-hidden">
                        <!-- Browser Chrome -->
                        <div class="flex items-center gap-2 px-4 py-3 bg-neutral-800">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="flex-1 flex justify-center">
                                <div class="bg-neutral-700 rounded-md px-4 py-1 text-neutral-400 text-xs">
                                    restozen.id/dashboard
                                </div>
                            </div>
                        </div>
                        <!-- Dashboard Content Preview -->
                        <div class="bg-neutral-100 p-4 md:p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-4">
                                <div class="bg-white rounded-lg p-3 md:p-4 shadow-sm">
                                    <div class="text-xs text-neutral-500 mb-1">Penjualan Hari Ini</div>
                                    <div class="text-lg md:text-xl font-bold text-neutral-900">Rp 4.5M</div>
                                    <div class="text-xs text-secondary-500 mt-1">↑ 12% dari kemarin</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 md:p-4 shadow-sm">
                                    <div class="text-xs text-neutral-500 mb-1">Total Order</div>
                                    <div class="text-lg md:text-xl font-bold text-neutral-900">127</div>
                                    <div class="text-xs text-secondary-500 mt-1">↑ 8% dari kemarin</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 md:p-4 shadow-sm">
                                    <div class="text-xs text-neutral-500 mb-1">Meja Terisi</div>
                                    <div class="text-lg md:text-xl font-bold text-neutral-900">18/25</div>
                                    <div class="text-xs text-accent-500 mt-1">72% kapasitas</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 md:p-4 shadow-sm">
                                    <div class="text-xs text-neutral-500 mb-1">Order Pending</div>
                                    <div class="text-lg md:text-xl font-bold text-primary-500">5</div>
                                    <div class="text-xs text-neutral-400 mt-1">Menunggu diproses</div>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-3 gap-3 md:gap-4">
                                <div class="md:col-span-2 bg-white rounded-lg p-4 shadow-sm h-32 md:h-40"></div>
                                <div class="bg-white rounded-lg p-4 shadow-sm h-32 md:h-40"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script>
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");
        const navLinks = document.getElementById("mobile-navLinks");

        const openMenuHandler = () => {
            navLinks.classList.remove("-translate-x-full");
            navLinks.classList.add("translate-x-0");
        }

        const closeMenuHandler = () => {
            navLinks.classList.remove("translate-x-0");
            navLinks.classList.add("-translate-x-full");
        }

        if(openMenu) openMenu.addEventListener("click", openMenuHandler);
        if(closeMenu) closeMenu.addEventListener("click", closeMenuHandler);
    </script>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mb-4">Fitur Lengkap untuk Bisnis Anda</h2>
                <p class="text-neutral-600 text-lg max-w-2xl mx-auto">Semua yang Anda butuhkan untuk mengelola restoran modern dalam satu platform.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: POS -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-primary-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-500 transition-colors">
                        <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Point of Sale</h3>
                    <p class="text-neutral-600">Sistem kasir yang cepat dan mudah digunakan. Proses pesanan dalam hitungan detik dengan antarmuka intuitif.</p>
                </div>
                <!-- Feature 2: Menu Management -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-secondary-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-secondary-500 transition-colors">
                        <svg class="w-7 h-7 text-secondary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Manajemen Menu</h3>
                    <p class="text-neutral-600">Atur menu dengan kategori, harga, gambar, dan ketersediaan. Update menu kapan saja secara real-time.</p>
                </div>
                <!-- Feature 3: Table Management -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-accent-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="w-14 h-14 bg-accent-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent-500 transition-colors">
                        <svg class="w-7 h-7 text-accent-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Manajemen Meja</h3>
                    <p class="text-neutral-600">Pantau status meja secara real-time. Atur area, kapasitas, dan generate QR code untuk setiap meja.</p>
                </div>
                <!-- Feature 4: QR Order -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-primary-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-500 transition-colors">
                        <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">QR Order</h3>
                    <p class="text-neutral-600">Pelanggan scan QR di meja untuk melihat menu dan order langsung. Tanpa perlu panggil pelayan.</p>
                </div>
                <!-- Feature 5: Kitchen Display -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-secondary-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                    <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-secondary-500 transition-colors">
                        <svg class="w-7 h-7 text-secondary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Kitchen Display</h3>
                    <p class="text-neutral-600">Dapur terima pesanan langsung di layar. Update status masakan real-time tanpa kertas.</p>
                </div>
                <!-- Feature 6: Reports -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:border-accent-200 hover:shadow-lg transition-all group" data-aos="fade-up" data-aos-duration="600" data-aos-delay="500">
                    <div class="w-14 h-14 bg-accent-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent-500 transition-colors">
                        <svg class="w-7 h-7 text-accent-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Laporan Keuangan</h3>
                    <p class="text-neutral-600">Laporan penjualan, metode pembayaran, dan menu terlaris. Export ke PDF untuk analisis bisnis.</p>
                </div>
            </div>
            <!-- Additional Features -->
            <div class="mt-12 grid md:grid-cols-2 gap-8">
                <div class="bg-neutral-50 p-8 rounded-2xl flex items-start gap-6">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Manajemen Karyawan</h3>
                        <p class="text-neutral-600">Kelola staff dengan role berbeda, PIN akses cepat, dan tracking aktivitas per outlet.</p>
                    </div>
                </div>
                <div class="bg-neutral-50 p-8 rounded-2xl flex items-start gap-6">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Multi-Outlet Support</h3>
                        <p class="text-neutral-600">Kelola beberapa cabang restoran dari satu dashboard. Data terpisah dan aman per outlet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 bg-neutral-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mb-4">Pilih Paket yang Sesuai</h2>
                <p class="text-neutral-600 text-lg">Mulai gratis 14 hari, tanpa kartu kredit.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter Plan -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-duration="600">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">Starter</h3>
                        <p class="text-neutral-500 text-sm">Untuk restoran kecil yang baru mulai</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-neutral-900">Rp 299K</span>
                        <span class="text-neutral-500">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            1 Outlet
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Maksimal 20 Meja
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            POS & Menu Management
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            QR Order
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Laporan Dasar
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 px-6 border-2 border-neutral-300 text-neutral-700 font-medium rounded-xl hover:border-primary-500 hover:text-primary-500 transition">
                        Mulai Gratis
                    </a>
                </div>
                <!-- Professional Plan -->
                <div class="bg-neutral-900 p-8 rounded-2xl border-2 border-primary-500 shadow-xl relative" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary-500 text-white text-sm font-medium px-4 py-1 rounded-full">
                        Populer
                    </div>
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-white mb-2">Professional</h3>
                        <p class="text-neutral-400 text-sm">Untuk restoran yang berkembang</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-white">Rp 599K</span>
                        <span class="text-neutral-400">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Hingga 3 Outlet
                        </li>
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Meja Unlimited
                        </li>
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Semua Fitur Starter
                        </li>
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Kitchen Display System
                        </li>
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Laporan Lengkap + Export
                        </li>
                        <li class="flex items-center gap-3 text-neutral-300">
                            <svg class="w-5 h-5 text-secondary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Manajemen Karyawan
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 px-6 bg-primary-500 text-white font-medium rounded-xl hover:bg-primary-600 transition">
                        Mulai Gratis
                    </a>
                </div>
                <!-- Enterprise Plan -->
                <div class="bg-white p-8 rounded-2xl border border-neutral-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">Enterprise</h3>
                        <p class="text-neutral-500 text-sm">Untuk jaringan restoran besar</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-neutral-900">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Outlet Unlimited
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Semua Fitur Professional
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dedicated Support
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Custom Integration
                        </li>
                        <li class="flex items-center gap-3 text-neutral-600">
                            <svg class="w-5 h-5 text-secondary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            SLA Guarantee
                        </li>
                    </ul>
                    <a href="#contact" class="block w-full text-center py-3 px-6 border-2 border-neutral-300 text-neutral-700 font-medium rounded-xl hover:border-primary-500 hover:text-primary-500 transition">
                        Hubungi Sales
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-primary-500">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up" data-aos-duration="800">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">Siap Tingkatkan Bisnis Restoran Anda?</h2>
            <p class="text-primary-100 text-lg mb-10">Mulai gratis 14 hari. Tidak perlu kartu kredit. Batalkan kapan saja.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary-600 font-semibold text-lg px-8 py-4 rounded-xl hover:bg-neutral-100 transition">
                Daftar Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="py-16 px-4 sm:px-6 lg:px-8 bg-neutral-900">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('images/logo-premium-nobg-white.png') }}" alt="RestoZen Logo" class="h-8 w-auto">
                    </div>
                    <p class="text-neutral-400 mb-6 max-w-sm">Sistem POS modern untuk restoran Indonesia. Kelola bisnis lebih efisien dengan teknologi terkini.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center hover:bg-neutral-700 transition">
                            <svg class="w-5 h-5 text-neutral-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-neutral-800 rounded-lg flex items-center justify-center hover:bg-neutral-700 transition">
                            <svg class="w-5 h-5 text-neutral-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Produk</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-neutral-400 hover:text-white transition">Fitur</a></li>
                        <li><a href="#pricing" class="text-neutral-400 hover:text-white transition">Harga</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition">Roadmap</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-3">
                        <li class="text-neutral-400">support@restozen.id</li>
                        <li class="text-neutral-400">+62 812-3456-7890</li>
                        <li class="text-neutral-400">Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-neutral-800 pt-8 text-center">
                <p class="text-neutral-500 text-sm">&copy; {{ date('Y') }} RestoZen. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out',
        });
    </script>
</body>
</html>
