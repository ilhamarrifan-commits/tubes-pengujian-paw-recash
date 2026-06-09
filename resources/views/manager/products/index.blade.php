@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Menu Management</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('manager.products.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search menu..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </form>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('manager.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add New
                    Product</a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    @if(auth()->user()->role === 'admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50"
                                    class="rounded object-fit-cover">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width:50px;height:50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td><span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span></td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Unavailable</span>
                            @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                            <td>
                                <a href="{{ route('manager.products.edit', $product) }}" class="btn btn-sm btn-outline-dark"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('manager.products.destroy', $product) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($products->isEmpty())
            <div class="text-center py-5 text-muted">No products found. Add one to get started.</div>
        @endif
    </div>
@endsection