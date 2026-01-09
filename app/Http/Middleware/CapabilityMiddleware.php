<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CapabilityMiddleware
{
    /**
     * Available capabilities and their descriptions.
     */
    public const CAPABILITIES = [
        'dashboard' => [
            'name' => 'Dashboard',
            'description' => 'Akses ke dashboard dan ringkasan bisnis',
            'icon' => 'dashboard',
        ],
        'menu_management' => [
            'name' => 'Manajemen Menu',
            'description' => 'Kelola kategori dan item menu',
            'icon' => 'menu',
        ],
        'table_management' => [
            'name' => 'Manajemen Meja',
            'description' => 'Kelola area dan denah meja',
            'icon' => 'table',
        ],
        'waiter' => [
            'name' => 'Pelayan',
            'description' => 'Buat pesanan baru dan tambah item',
            'icon' => 'order',
        ],
        'cashier' => [
            'name' => 'Kasir',
            'description' => 'Proses pembayaran pesanan',
            'icon' => 'cashier',
        ],
        'orders' => [
            'name' => 'Riwayat Pesanan',
            'description' => 'Lihat daftar dan detail pesanan',
            'icon' => 'history',
        ],
        'kitchen' => [
            'name' => 'Kitchen Display',
            'description' => 'Akses kitchen display system',
            'icon' => 'kitchen',
        ],
        'employees' => [
            'name' => 'Karyawan',
            'description' => 'Kelola data karyawan',
            'icon' => 'employees',
        ],
        'reports' => [
            'name' => 'Laporan',
            'description' => 'Akses laporan keuangan dan penjualan',
            'icon' => 'report',
        ],
    ];

    /**
     * Get role presets for quick assignment.
     */
    public static function getRolePresets(): array
    {
        return [
            'owner' => [
                'name' => 'Owner',
                'description' => 'Akses penuh ke semua fitur',
                'capabilities' => array_keys(self::CAPABILITIES),
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Akses ke semua fitur kecuali pengaturan langganan',
                'capabilities' => array_keys(self::CAPABILITIES),
            ],
            'waiter' => [
                'name' => 'Pelayan',
                'description' => 'Buat pesanan dan antar makanan',
                'capabilities' => ['dashboard', 'waiter', 'orders'],
            ],
            'cashier' => [
                'name' => 'Kasir',
                'description' => 'Proses pembayaran',
                'capabilities' => ['dashboard', 'cashier', 'orders'],
            ],
            'kitchen' => [
                'name' => 'Kitchen Staff',
                'description' => 'Akses ke kitchen display',
                'capabilities' => ['dashboard', 'kitchen'],
            ],
        ];
    }

    /**
     * Handle an incoming request.
     * Check if user has required capability at current outlet.
     *
     * Usage in routes:
     * - Route::middleware('capability:cashier') - Requires cashier capability
     * - Route::middleware('capability:cashier,orders') - Requires cashier OR orders capability
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$requiredCapabilities): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get current outlet from session
        $currentOutletId = session('current_outlet_id');

        if (!$currentOutletId) {
            // Fallback to user's default outlet
            $outlet = $user->defaultOutlet();
            if ($outlet) {
                session(['current_outlet_id' => $outlet->id]);
                $currentOutletId = $outlet->id;
            }
        }

        if (!$currentOutletId) {
            abort(403, 'Tidak ada outlet yang ditetapkan untuk user ini.');
        }

        // Get user's pivot data at current outlet
        $pivot = $user->outlets()->where('outlet_id', $currentOutletId)->first();

        if (!$pivot) {
            abort(403, 'User tidak memiliki akses ke outlet ini.');
        }

        $userCapabilities = $pivot->pivot->capabilities ?? [];

        // Ensure capabilities is an array
        if (is_string($userCapabilities)) {
            $userCapabilities = json_decode($userCapabilities, true) ?? [];
        }

        // Check if user has at least one of the required capabilities
        $hasCapability = false;
        foreach ($requiredCapabilities as $capability) {
            if (in_array($capability, $userCapabilities)) {
                $hasCapability = true;
                break;
            }
        }

        if (!$hasCapability) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini. Diperlukan: ' . implode(' atau ', $requiredCapabilities));
        }

        return $next($request);
    }
}
