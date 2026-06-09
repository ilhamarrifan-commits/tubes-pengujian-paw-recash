@extends('layouts.app')

@section('content')
    <div class="row h-100">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="fw-bold m-0">Riwayat Transaksi</h2>
                    @if(auth()->user()->role === 'admin')
                        <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm d-none" id="btnDeleteSelected" onclick="confirmBulkDelete()">
                            <i class="bi bi-trash-fill me-2"></i>Hapus Terpilih
                        </button>
                    @endif
                </div>

                <form action="{{ route(auth()->user()->role . '.history') }}" method="GET" class="d-flex gap-3">
                    <select name="sort" class="form-select border-0 shadow-sm rounded-pill ps-3 pe-5"
                        onchange="this.form.submit()" style="width: auto;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>

                    <input type="month" name="filter_month" class="form-control border-0 shadow-sm rounded-pill px-3"
                        value="{{ request('filter_month') }}" onchange="this.form.submit()"
                        style="width: 170px; background-color: #fff; cursor: pointer;"
                        onclick="try{this.showPicker()}catch(e){}">

                    <input type="text" name="search" class="form-control border-0 shadow-sm rounded-pill px-3"
                        placeholder="Cari nama, id, produk..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-dark rounded-pill px-3 shadow-sm"><i
                            class="bi bi-search"></i></button>
                </form>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form id="bulkDeleteForm" action="{{ route('admin.history.bulkDelete') }}" method="POST">
                    @csrf
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                @if(auth()->user()->role === 'admin')
                                    <th width="40"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                @endif
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
                                    @if(auth()->user()->role === 'admin')
                                        <td><input type="checkbox" name="ids[]" value="{{ $order->id }}" class="form-check-input order-checkbox"></td>
                                    @endif
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        @if($order->order_type == 'dine_in')
                                            <span class="badge bg-primary">Dine In</span> <br> <small class="text-muted">Table
                                                {{ $order->table_number }}</small>
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
                </form>
                @if($orders->isEmpty())
                    <div class="text-center py-5 text-muted">No history found.</div>
                @endif
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

@push('scripts')
        <script>
            if (document.getElementById('selectAll')) {
                const selectAll = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.order-checkbox');
                const btnDeleteSelected = document.getElementById('btnDeleteSelected');

                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = selectAll.checked;
                    });
                    toggleDeleteButton();
                });

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', toggleDeleteButton);
                });

                function toggleDeleteButton() {
                    const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
                    if (checkedCount > 0) {
                        btnDeleteSelected.classList.remove('d-none');
                    } else {
                        btnDeleteSelected.classList.add('d-none');
                    }
                }
            }

            function confirmBulkDelete() {
                const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
                if (confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} riwayat transaksi yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            }
        </script>
    @endpush
@endsection