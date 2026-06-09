@extends('layouts.app')

@section('content')
<div class="row h-100">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
             <div class="d-flex align-items-center gap-3">
                <a href="{{ route('cashier.dashboard') }}" class="btn btn-outline-dark rounded-circle"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold m-0">Order History</h2>
             </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Type/Table</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            @if($order->order_type == 'dine_in')
                                <span class="badge bg-primary">Dine In</span> <br> <small class="text-muted">Table {{ $order->table_number }}</small>
                            @else
                                <span class="badge bg-warning text-dark">Take Away</span>
                            @endif
                        </td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($order->items as $item)
                                <li class="small text-muted">{{ $item->product->name }} x{{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="fw-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td><span class="badge bg-success">Paid</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($orders->isEmpty())
                <div class="text-center py-5 text-muted">No history found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
