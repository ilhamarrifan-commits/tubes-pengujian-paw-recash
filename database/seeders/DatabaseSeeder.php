<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $manager = User::create([
            'name' => 'Manager One',
            'email' => 'manager@recash.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $admin = User::create([
            'name' => 'Admin One',
            'email' => 'admin@recash.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $cashier = User::create([
            'name' => 'Cashier One',
            'email' => 'cashier@recash.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        $cashier2 = User::create([
            'name' => 'Cashier Two',
            'email' => 'cashier2@recash.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        // 2. Categories
        $categories = [
            'Meals' => 'meals',
            'Drink' => 'drink',
            'Vegetarian' => 'vegetarian',
            'Dessert' => 'dessert',
        ];

        $catIds = [];
        foreach ($categories as $name => $slug) {
            $cat = Category::create(['name' => $name, 'slug' => $slug]);
            $catIds[$slug] = $cat->id;
        }

        // 3. Products
        $products = [
            [
                'category_id' => $catIds['meals'],
                'name' => 'Sate Taichan',
                'description' => 'Spicy chicken satay',
                'price' => 35000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['meals'],
                'name' => 'Gurami Asam Manis',
                'description' => 'Sweet and sour carp',
                'price' => 85000,
                'stock' => 20,
                'is_available' => true,
            ],
             [
                'category_id' => $catIds['meals'],
                'name' => 'Kebab Daging',
                'description' => 'Beef Kebab',
                'price' => 48000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_id' => $catIds['drink'],
                'name' => 'Jus Melon',
                'description' => 'Fresh Melon Juice',
                'price' => 15000,
                'stock' => 100,
                'is_available' => true,
            ],
             [
                'category_id' => $catIds['dessert'],
                'name' => 'Es Ketan Hitam',
                'description' => 'Black sticky rice ice',
                'price' => 20000,
                'stock' => 40,
                'is_available' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        // 4. Dummy Orders (Last 30 days)
        $allProducts = Product::all();
        
        for ($i = 0; $i < 50; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30));
            $user = rand(0, 1) ? $cashier : $cashier2;
            
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => 'Customer ' . $i,
                'table_number' => rand(1, 20),
                'total_amount' => 0, // Will calc below
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $total = 0;
            $itemsCount = rand(1, 4);
            
            for ($j = 0; $j < $itemsCount; $j++) {
                $prod = $allProducts->random();
                $qty = rand(1, 3);
                $price = $prod->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $prod->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                
                $total += $price * $qty;
            }
            
            $order->update(['total_amount' => $total * 1.1]); // + Tax
        }
    }
}
