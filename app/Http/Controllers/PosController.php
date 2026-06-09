<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($q) {
            $q->where('is_available', true);
        }])->get();

        return view('cashier.index', compact('categories'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'table_number' => 'nullable|string',
            'order_type' => 'required|in:dine_in,take_away',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_amount' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'table_number' => $validated['table_number'] ?? null,
                'order_type' => $validated['order_type'],
                'total_amount' => $validated['total_amount'],
                'payment_status' => 'paid', 
                'payment_method' => 'cash', 
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // Update stock if needed (optional based on requirements)
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json(['success' => true, 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->with('items.product')
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('cashier.history', compact('orders'));
    }

    public function profile()
    {
        return view('cashier.profile');
    }
}
