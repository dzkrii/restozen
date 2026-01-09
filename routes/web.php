<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TableAreaController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

/*
|--------------------------------------------------------------------------
| Subscription Routes (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
    Route::get('/checkout/{plan:slug}', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/process-checkout', [SubscriptionController::class, 'processCheckout'])->name('process-checkout');
    Route::get('/payment/{subscription}', [SubscriptionController::class, 'payment'])->name('payment');
    Route::get('/finish', [SubscriptionController::class, 'finish'])->name('finish');
    Route::get('/pending/{subscription}', [SubscriptionController::class, 'pending'])->name('pending');
    Route::get('/check-status/{subscription}', [SubscriptionController::class, 'checkStatus'])->name('check-status');
    Route::get('/success/{subscription}', [SubscriptionController::class, 'success'])->name('success');
    
    // Handle success callback from client-side
    Route::post('/handle-success', [SubscriptionController::class, 'handleSuccess'])->name('handle-success');
    
    // Midtrans Notification Callback (webhook)
    Route::post('/notification', [SubscriptionController::class, 'notification'])->name('notification');
});

// Registration with Subscription
Route::get('/register/subscription', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createWithSubscription'])
    ->name('register.subscription');
Route::post('/register/subscription', [App\Http\Controllers\Auth\RegisteredUserController::class, 'storeWithSubscription'])
    ->name('register.subscription.store');

/*
|--------------------------------------------------------------------------
| Authenticated Routes with Outlet Access
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'outlet.access'])->group(function () {
    
    // Dashboard - accessible by all authenticated users with outlet access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Menu Management - Requires menu_management capability
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:menu_management')->group(function () {
        // Menu Categories
        Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);
        Route::post('menu-categories/reorder', [MenuCategoryController::class, 'reorder'])->name('menu-categories.reorder');

        // Menu Items
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
        Route::post('menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
            ->name('menu-items.toggle-availability');
    });

    /*
    |--------------------------------------------------------------------------
    | Table Management - Requires table_management capability
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:table_management')->group(function () {
        // Table Areas
        Route::resource('table-areas', TableAreaController::class)->except(['show']);

        // Tables
        Route::resource('tables', TableController::class)->except(['show']);
        Route::post('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.update-status');
        Route::post('tables/{table}/regenerate-qr', [TableController::class, 'regenerateQr'])->name('tables.regenerate-qr');
        Route::get('tables/{table}/download-qr', [TableController::class, 'downloadQr'])->name('tables.download-qr');
        Route::get('tables/download-all-qrs', [TableController::class, 'downloadAllQrs'])->name('tables.download-all-qrs');
    });

    /*
    |--------------------------------------------------------------------------
    | Waiter/Pelayan - Requires waiter capability
    | - Create and manage orders
    | - Add items to existing orders
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:waiter')->group(function () {
        Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::get('orders/create/menu', [OrderController::class, 'selectMenu'])->name('orders.select-menu');
        Route::get('orders/create/menu/{table}', [OrderController::class, 'selectMenu'])->name('orders.select-menu.table');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
        
        // Edit/Add items to existing orders
        Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Cashier/Kasir - Requires cashier capability
    | - Process payments only
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:cashier')->group(function () {
        Route::get('orders/{order}/payment', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Orders View - Requires orders, waiter, or cashier capability
    | - View order list and details
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:orders,waiter,cashier')->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    });

    /*
    |--------------------------------------------------------------------------
    | Kitchen Display System - Requires kitchen capability
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:kitchen')->group(function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/api/check-new-orders', [KitchenController::class, 'checkNewOrders'])->name('kitchen.check-new');
        Route::patch('/kitchen/items/{item}', [KitchenController::class, 'updateItemStatus'])->name('kitchen.update-item');
        Route::post('/kitchen/orders/{order}/ready', [KitchenController::class, 'markOrderReady'])->name('kitchen.mark-ready');
    });

    /*
    |--------------------------------------------------------------------------
    | Employee Management - Requires employees capability
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:employees')->group(function () {
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->except(['show']);
        Route::post('employees/{employee}/toggle-status', [\App\Http\Controllers\EmployeeController::class, 'toggleStatus'])
            ->name('employees.toggle-status');
        Route::post('employees/{employee}/reset-pin', [\App\Http\Controllers\EmployeeController::class, 'resetPin'])
            ->name('employees.reset-pin');
    });

    /*
    |--------------------------------------------------------------------------
    | Financial Reports - Requires reports capability
    |--------------------------------------------------------------------------
    */
    Route::middleware('capability:reports')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/payment-methods', [ReportController::class, 'paymentMethods'])->name('payment-methods');
        Route::get('/top-selling', [ReportController::class, 'topSelling'])->name('top-selling');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
    });
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Auth Only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| QR Ordering - Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::prefix('order')->name('qr.')->group(function () {
    Route::get('/{outletSlug}/{tableQr}', [\App\Http\Controllers\QrOrderController::class, 'menu'])->name('menu');
    Route::post('/cart/add', [\App\Http\Controllers\QrOrderController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update', [\App\Http\Controllers\QrOrderController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{index}', [\App\Http\Controllers\QrOrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [\App\Http\Controllers\QrOrderController::class, 'viewCart'])->name('cart.view');
    Route::post('/submit', [\App\Http\Controllers\QrOrderController::class, 'submitOrder'])->name('submit');
    Route::get('/confirmation/{orderNumber}', [\App\Http\Controllers\QrOrderController::class, 'confirmation'])->name('confirmation');
    Route::get('/track/{orderNumber}', [\App\Http\Controllers\QrOrderController::class, 'trackOrder'])->name('track');
    Route::get('/receipt/{orderNumber}', [\App\Http\Controllers\QrOrderController::class, 'printReceipt'])->name('receipt');
    Route::get('/status/{orderNumber}', [\App\Http\Controllers\QrOrderController::class, 'getOrderStatus'])->name('status');
});

require __DIR__.'/auth.php';
