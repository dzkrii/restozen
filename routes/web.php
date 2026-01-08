<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableAreaController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'outlet.access'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Menu & Table Management Routes
Route::middleware(['auth', 'verified', 'outlet.access'])->group(function () {
    // Menu Categories
    Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);
    Route::post('menu-categories/reorder', [MenuCategoryController::class, 'reorder'])->name('menu-categories.reorder');

    // Menu Items
    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::post('menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
        ->name('menu-items.toggle-availability');

    // Table Areas
    Route::resource('table-areas', TableAreaController::class)->except(['show']);

    // Tables
    Route::resource('tables', TableController::class)->except(['show']);
    Route::post('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::post('tables/{table}/regenerate-qr', [TableController::class, 'regenerateQr'])->name('tables.regenerate-qr');
    Route::get('tables/{table}/download-qr', [TableController::class, 'downloadQr'])->name('tables.download-qr');

    // Orders
    Route::get('orders/create/menu', [OrderController::class, 'selectMenu'])->name('orders.select-menu');
    Route::get('orders/create/menu/{table}', [OrderController::class, 'selectMenu'])->name('orders.select-menu.table');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::resource('orders', OrderController::class);

    // Payments
    Route::get('orders/{order}/payment', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');

    // Kitchen Display System
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/items/{item}', [KitchenController::class, 'updateItemStatus'])->name('kitchen.update-item');
    Route::post('/kitchen/orders/{order}/ready', [KitchenController::class, 'markOrderReady'])->name('kitchen.mark-ready');
});

require __DIR__.'/auth.php';
