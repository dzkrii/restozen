<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\TableArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $outletId = Auth::user()->current_outlet_id;

        $query = Order::where('outlet_id', $outletId)
            ->with(['table', 'items', 'user'])
            ->latest();

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default to today
            $query->whereDate('created_at', today());
        }

        $orders = $query->paginate(20);

        // Order statistics
        $todayOrders = Order::where('outlet_id', $outletId)
            ->whereDate('created_at', today())
            ->count();

        $todayRevenue = Order::where('outlet_id', $outletId)
            ->whereDate('created_at', today())
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total_amount');

        $pendingCount = Order::where('outlet_id', $outletId)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_PREPARING])
            ->count();

        return view('orders.index', compact('orders', 'todayOrders', 'todayRevenue', 'pendingCount'));
    }

    /**
     * Show the form for creating a new order - Table selection.
     */
    public function create(Request $request)
    {
        $outletId = Auth::user()->current_outlet_id;

        // Get table areas with tables
        $tableAreas = TableArea::where('outlet_id', $outletId)
            ->where('is_active', true)
            ->with(['tables' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Tables without area
        $tablesWithoutArea = Table::where('outlet_id', $outletId)
            ->whereNull('table_area_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $orderType = $request->get('type', 'dine_in');

        return view('orders.create', compact('tableAreas', 'tablesWithoutArea', 'orderType'));
    }

    /**
     * Show menu selection for the selected table.
     */
    public function selectMenu(Request $request, Table $table = null)
    {
        $outletId = Auth::user()->current_outlet_id;

        // Verify table belongs to outlet
        if ($table && $table->outlet_id !== $outletId) {
            abort(403);
        }

        $orderType = $request->get('type', 'dine_in');

        // Get categories with menu items
        $categories = MenuCategory::where('outlet_id', $outletId)
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_available', true)
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('orders.select-menu', compact('table', 'categories', 'orderType'));
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'order_type' => 'required|in:dine_in,takeaway,delivery,qr_order',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'guest_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $outletId = Auth::user()->current_outlet_id;

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'outlet_id' => $outletId,
                'table_id' => $request->table_id,
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'status' => Order::STATUS_PENDING,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'guest_count' => $request->guest_count ?? 1,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($request->items as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'menu_item_name' => $menuItem->name,
                    'unit_price' => $menuItem->price,
                    'quantity' => $itemData['quantity'],
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Update table status if dine-in
            if ($request->table_id && $request->order_type === 'dine_in') {
                Table::where('id', $request->table_id)->update(['status' => 'occupied']);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        $order->load(['table', 'items.menuItem', 'user', 'payments']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for adding items to existing order.
     */
    public function edit(Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        // Can only edit pending/confirmed orders
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_PREPARING])) {
            return back()->with('error', 'Pesanan ini tidak dapat diedit lagi.');
        }

        $order->load(['table', 'items']);

        // Get categories with menu items
        $categories = MenuCategory::where('outlet_id', $outletId)
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_active', true)
                    ->where('is_available', true)
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('orders.edit', compact('order', 'categories'));
    }

    /**
     * Update the specified order (add items).
     */
    public function update(Request $request, Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Add new items
            foreach ($request->items as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);

                // Check if item already exists in order
                $existingItem = $order->items()
                    ->where('menu_item_id', $menuItem->id)
                    ->where('status', OrderItem::STATUS_PENDING)
                    ->first();

                if ($existingItem) {
                    // Update quantity
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $itemData['quantity'],
                        'subtotal' => $existingItem->unit_price * ($existingItem->quantity + $itemData['quantity']),
                    ]);
                } else {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'menu_item_name' => $menuItem->name,
                        'unit_price' => $menuItem->price,
                        'quantity' => $itemData['quantity'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Item berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambah item: ' . $e->getMessage());
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Handle status-specific actions
        if ($request->status === Order::STATUS_CONFIRMED) {
            $order->update(['confirmed_at' => now()]);
        } elseif ($request->status === Order::STATUS_COMPLETED) {
            $order->complete();
        } elseif ($request->status === Order::STATUS_CANCELLED && $order->table) {
            $order->table->release();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate!',
                'status' => $order->status,
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }

    /**
     * Show receipt for printing.
     */
    public function receipt(Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        $order->load(['outlet', 'table', 'items', 'payments']);

        return view('receipts.show', compact('order'));
    }

    /**
     * Delete/Cancel an order.
     */
    public function destroy(Order $order)
    {
        $outletId = Auth::user()->current_outlet_id;

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        // Can only delete pending orders
        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dihapus.');
        }

        // Release table if occupied
        if ($order->table) {
            $order->table->release();
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}
