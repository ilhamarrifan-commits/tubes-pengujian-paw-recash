<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();
        return view('manager.products.index', compact('products'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin')
            abort(403, 'Unauthorized action.');
        $categories = Category::all();
        return view('manager.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin')
            abort(403, 'Unauthorized action.');
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('manager.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (auth()->user()->role !== 'admin')
            abort(403, 'Unauthorized action.');
        $categories = Category::all();
        return view('manager.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->user()->role !== 'admin')
            abort(403, 'Unauthorized action.');
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('manager.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->role !== 'admin')
            abort(403, 'Unauthorized action.');
        $product->delete();
        return redirect()->route('manager.products.index')->with('success', 'Product deleted successfully.');
    }
}
