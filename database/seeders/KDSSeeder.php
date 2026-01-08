<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class KDSSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $outlet = Outlet::first();
        
        if (!$user || !$outlet) {
            $this->command->error("User or Outlet not found.");
            return;
        }

        // Ensure we have a table
        $table = Table::firstOrCreate(
            ['outlet_id' => $outlet->id, 'number' => 'T-KDS-1'],
            ['name' => 'KDS Test Table', 'capacity' => 4, 'status' => 'available']
        );

        // Ensure we have menu items
        $category = MenuCategory::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Main Course'],
            ['slug' => 'main-course']
        );

        $item1 = MenuItem::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Nasi Goreng Special'],
            ['menu_category_id' => $category->id, 'price' => 25000, 'is_available' => true, 'slug' => 'nasi-goreng-special']
        );

        $item2 = MenuItem::firstOrCreate(
            ['outlet_id' => $outlet->id, 'name' => 'Ice Tea'],
            ['menu_category_id' => $category->id, 'price' => 5000, 'is_available' => true, 'slug' => 'ice-tea']
        );

        // Create Order 1 (Confirmed - Should Show)
        $order1 = Order::create([
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'user_id' => $user->id,
            'status' => Order::STATUS_CONFIRMED,
            'customer_name' => 'John Doe',
            'confirmed_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $item1->id,
            'menu_item_name' => $item1->name,
            'unit_price' => $item1->price,
            'quantity' => 2,
            'status' => OrderItem::STATUS_PENDING,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_item_id' => $item2->id,
            'menu_item_name' => $item2->name,
            'unit_price' => $item2->price,
            'quantity' => 1,
            'status' => OrderItem::STATUS_PREPARING, // Already preparing
        ]);

        // Create Order 2 (Pending - Should NOT Show)
        $order2 = Order::create([
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'user_id' => $user->id,
            'status' => Order::STATUS_PENDING, // Not sent to kitchen yet
            'customer_name' => 'Jane Draft',
        ]);

        $this->command->info("KDS Test Data Seeded. Order #{$order1->order_number} created.");
    }
}
