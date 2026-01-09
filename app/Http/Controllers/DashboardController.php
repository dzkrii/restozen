<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()

    {
        $user = auth()->user();
        $outlet = $user->currentOutlet;

        $stats = [
            'total_menu' => 0,
            'total_categories' => 0,
            'total_tables' => 0,
            'available_tables' => 0,
            'occupied_tables' => 0,
            'reserved_tables' => 0,
            'maintenance_tables' => 0,
            'today_orders' => 0,
            'today_revenue' => 0,
            'this_month_revenue' => 0,
            'pending_orders' => 0,
            'total_orders' => 0,
            'total_revenue' => 0,
            'orders_growth' => 0,
            'revenue_growth' => 0,
            'monthly_sales' => [],
            'months' => [],

        ];

        if ($outlet) {
            $stats['total_menu'] = MenuItem::where('outlet_id', $outlet->id)->count();
            $stats['total_categories'] = MenuCategory::where('outlet_id', $outlet->id)->count();
            $stats['total_tables'] = Table::where('outlet_id', $outlet->id)->count();
            $stats['available_tables'] = Table::where('outlet_id', $outlet->id)->where('status', Table::STATUS_AVAILABLE)->count();
            $stats['occupied_tables'] = Table::where('outlet_id', $outlet->id)->where('status', Table::STATUS_OCCUPIED)->count();
            $stats['reserved_tables'] = Table::where('outlet_id', $outlet->id)->where('status', Table::STATUS_RESERVED)->count();
            $stats['maintenance_tables'] = Table::where('outlet_id', $outlet->id)->where('status', Table::STATUS_MAINTENANCE)->count();

            // Order Stats
            $stats['today_orders'] = Order::where('outlet_id', $outlet->id)
                ->whereDate('created_at', today())
                ->count();
            
            $stats['today_revenue'] = Order::where('outlet_id', $outlet->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereDate('completed_at', today())
                ->sum('total_amount');

            $stats['pending_orders'] = Order::where('outlet_id', $outlet->id)
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_PREPARING,
                    Order::STATUS_READY,
                    Order::STATUS_SERVED
                ])->count();

            // Total Stats & Growth
            $now = now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
            $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

            // Total Orders
            $stats['total_orders'] = Order::where('outlet_id', $outlet->id)->count();
            
            $thisMonthOrders = Order::where('outlet_id', $outlet->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $lastMonthOrders = Order::where('outlet_id', $outlet->id)
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->count();

            if ($lastMonthOrders > 0) {
                $stats['orders_growth'] = (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
            } elseif ($thisMonthOrders > 0) {
                $stats['orders_growth'] = 100;
            }

            // Total Revenue
            $stats['total_revenue'] = Order::where('outlet_id', $outlet->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('total_amount');

            $thisMonthRevenue = Order::where('outlet_id', $outlet->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');
            
            $stats['this_month_revenue'] = $thisMonthRevenue;

            $lastMonthRevenue = Order::where('outlet_id', $outlet->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereBetween('completed_at', [$startOfLastMonth, $endOfLastMonth])
                ->sum('total_amount');

            if ($lastMonthRevenue > 0) {
                $stats['revenue_growth'] = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
            } elseif ($thisMonthRevenue > 0) {
                $stats['revenue_growth'] = 100;
            }

            // Monthly Sales Chart Data (Last 12 months)
            for ($i = 11; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                
                $revenue = Order::where('outlet_id', $outlet->id)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('completed_at', [$start, $end])
                    ->sum('total_amount');
                
                $stats['monthly_sales'][] = $revenue;
                $stats['months'][] = $date->format('M');
            }
        }

        return view('dashboard', compact('stats'));
    }
}
