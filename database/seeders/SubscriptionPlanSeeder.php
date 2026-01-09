<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'MARUPOS Premium',
                'slug' => 'professional',
                'description' => 'Solusi lengkap untuk bisnis modern',
                'price_monthly' => 599000,
                'price_yearly' => 5750400, // 20% discount
                'max_outlets' => null, // Unlimited
                'max_tables' => null, // Unlimited
                'max_employees' => null, // Unlimited
                'features' => [
                    'Unlimited Table & Outlet',
                    'POS & Menu Management Lengkap',
                    'QR Order & Kitchen Display',
                    'Laporan Keuangan & Export PDF',
                    'Manajemen Karyawan & Shift',
                    'Priority Support 24/7',
                ],
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Untuk kebutuhan bisnis yang spesifik',
                'price_monthly' => 0, // Custom pricing
                'price_yearly' => 0, // Custom pricing
                'max_outlets' => null, // Unlimited
                'max_tables' => null, // Unlimited
                'max_employees' => null, // Unlimited
                'features' => [
                    'Custom Features & Integration',
                    'Dedicated Account Manager',
                    'On-site Training & Setup',
                    'White Label Option',
                    'Jaminan SLA 99.9%',
                    'Support Prioritas Khusus',
                ],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
