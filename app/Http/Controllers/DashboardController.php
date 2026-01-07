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
            'pending_orders' => 0,
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
        }

        return view('dashboard', compact('stats'));
    }
}
