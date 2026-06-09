@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Product</h2>
    <a href="{{ route('manager.products.index') }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card p-4" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('manager.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Price (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control">
                @if($product->image)
                    <div class="mt-2 text-muted small">Current: {{ basename($product->image) }}</div>
                @endif
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} id="availCheck">
            <label class="form-check-label" for="availCheck">
                Available for ordering
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Product</button>
    </form>
</div>
@endsection
