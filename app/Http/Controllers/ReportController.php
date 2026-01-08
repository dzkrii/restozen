<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard with overview statistics.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $outlet = $user->currentOutlet;

        if (!$outlet) {
            return redirect()->route('dashboard')->with('error', 'Please select an outlet first.');
        }

        // Default to today
        $startDate = $request->input('start_date', today()->toDateString());
        $endDate = $request->input('end_date', today()->toDateString());

        // Convert to Carbon instances for comparison
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Total Revenue (Completed Orders)
        $totalRevenue = Order::where('outlet_id', $outlet->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$start, $end])
            ->sum('total_amount');

        // Total Orders
        $totalOrders = Order::where('outlet_id', $outlet->id)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Completed Orders
        $completedOrders = Order::where('outlet_id', $outlet->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$start, $end])
            ->count();

        // Average Order Value
        $avgOrderValue = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;

        // Payment Methods Breakdown
        $paymentBreakdown = Payment::whereHas('order', function ($query) use ($outlet, $start, $end) {
                $query->where('outlet_id', $outlet->id)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('completed_at', [$start, $end]);
            })
            ->where('status', Payment::STATUS_COMPLETED)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Revenue Trend (Last 7 Days)
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $revenue = Order::where('outlet_id', $outlet->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereDate('completed_at', $date)
                ->sum('total_amount');
            
            $revenueTrend[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue,
            ];
        }

        return view('reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'avgOrderValue',
            'paymentBreakdown',
            'revenueTrend',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display detailed sales report.
     */
    public function sales(Request $request)
    {
        $user = auth()->user();
        $outlet = $user->currentOutlet;

        if (!$outlet) {
            return redirect()->route('dashboard')->with('error', 'Please select an outlet first.');
        }

        // Default to today
        $startDate = $request->input('start_date', today()->toDateString());
        $endDate = $request->input('end_date', today()->toDateString());
        $orderType = $request->input('order_type');
        $status = $request->input('status');

        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Build query
        $query = Order::where('outlet_id', $outlet->id)
            ->with(['table', 'user', 'payment'])
            ->whereBetween('created_at', [$start, $end]);

        if ($orderType) {
            $query->where('order_type', $orderType);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Summary statistics
        $totalOrders = $query->count();
        $totalRevenue = Order::where('outlet_id', $outlet->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$start, $end])
            ->sum('total_amount');
        
        $completedCount = Order::where('outlet_id', $outlet->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$start, $end])
            ->count();

        $avgOrderValue = $completedCount > 0 ? $totalRevenue / $completedCount : 0;

        return view('reports.sales', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'avgOrderValue',
            'startDate',
            'endDate',
            'orderType',
            'status'
        ));
    }

    /**
     * Display payment methods breakdown report.
     */
    public function paymentMethods(Request $request)
    {
        $user = auth()->user();
        $outlet = $user->currentOutlet;

        if (!$outlet) {
            return redirect()->route('dashboard')->with('error', 'Please select an outlet first.');
        }

        $startDate = $request->input('start_date', today()->toDateString());
        $endDate = $request->input('end_date', today()->toDateString());

        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Payment methods breakdown with transaction count
        $paymentData = Payment::whereHas('order', function ($query) use ($outlet, $start, $end) {
                $query->where('outlet_id', $outlet->id)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('completed_at', [$start, $end]);
            })
            ->where('status', Payment::STATUS_COMPLETED)
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('payment_method')
            ->orderBy('total_amount', 'desc')
            ->get();

        $totalRevenue = $paymentData->sum('total_amount');
        $totalTransactions = $paymentData->sum('transaction_count');

        return view('reports.payment-methods', compact(
            'paymentData',
            'totalRevenue',
            'totalTransactions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display top selling items and categories.
     */
    public function topSelling(Request $request)
    {
        $user = auth()->user();
        $outlet = $user->currentOutlet;

        if (!$outlet) {
            return redirect()->route('dashboard')->with('error', 'Please select an outlet first.');
        }

        $startDate = $request->input('start_date', today()->toDateString());
        $endDate = $request->input('end_date', today()->toDateString());

        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Top Selling Items
        $topItems = OrderItem::whereHas('order', function ($query) use ($outlet, $start, $end) {
                $query->where('outlet_id', $outlet->id)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('completed_at', [$start, $end]);
            })
            ->select(
                'menu_item_id',
                'menu_item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('menu_item_id', 'menu_item_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(20)
            ->get();

        // Top Selling Categories
        $topCategories = OrderItem::whereHas('order', function ($query) use ($outlet, $start, $end) {
                $query->where('outlet_id', $outlet->id)
                    ->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('completed_at', [$start, $end]);
            })
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('menu_categories', 'menu_items.menu_category_id', '=', 'menu_categories.id')
            ->select(
                'menu_categories.id as category_id',
                'menu_categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('menu_categories.id', 'menu_categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('reports.top-selling', compact(
            'topItems',
            'topCategories',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report to PDF.
     */
    public function exportPdf(Request $request)
    {
        // This will be implemented later if needed
        // For now, we'll use browser print functionality
        return redirect()->back()->with('info', 'Please use browser print (Ctrl+P) to save as PDF.');
    }
}
