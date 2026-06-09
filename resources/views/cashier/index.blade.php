@extends('layouts.app')

@section('content')
<div class="row h-100">
    <!-- Product Section -->
    <div class="col-md-9 d-flex flex-column h-100 pe-4">
        <!-- Top Bar -->
        <!-- Top Bar -->
        <div class="d-flex align-items-center mb-4 gap-4">
            <h1 class="fw-bold m-0 text-nowrap" style="font-size: 2rem;">Welcome</h1>
            
            <div class="flex-grow-1">
                 <div class="input-group rounded-pill border-0 shadow-sm w-100" style="background-color: white; overflow: hidden; padding: 5px 15px;">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 shadow-none" placeholder="Search menu">
                 </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-shrink-0">
                 <a href="{{ route('cashier.history') }}" class="btn btn-light rounded-pill shadow-sm py-2 px-3 fw-bold d-flex align-items-center gap-2 text-decoration-none text-dark" style="background-color: #EAE5D9;">
                    <i class="bi bi-clock-history"></i> History
                 </a>
            </div>
        </div>

        <!-- Categories -->
        <ul class="nav nav-pills mb-4 gap-3" id="categoryTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill px-4 py-2 border" data-bs-toggle="pill" data-bs-target="#all">All Menu</button>
            </li>
            @foreach($categories as $category)
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4 py-2 border" data-bs-toggle="pill" data-bs-target="#cat-{{ $category->id }}">{{ $category->name }}</button>
            </li>
            @endforeach
        </ul>

        <!-- Product Grid -->
        <div class="tab-content flex-grow-1 overflow-auto" style="scrollbar-width: none;">
            <div class="tab-pane fade show active" id="all">
                <div class="row g-3">
                    @foreach($categories->flatMap->products as $product)
                        @include('cashier.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
            @foreach($categories as $category)
            <div class="tab-pane fade" id="cat-{{ $category->id }}">
                <div class="row g-3">
                    @foreach($category->products as $product)
                        @include('cashier.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Cart Section -->
    <div class="col-md-3 d-flex flex-column h-100 ps-0">
        <!-- Header for Sidebar (Clock & Profile) aligned with Dine In/Take Away -->
        <div class="row align-items-center mb-4 ps-2 pe-3" style="height: 50px;">
            <!-- Left: Clock (Centered over Dine In) -->
            <div class="col-6 d-flex justify-content-center">
                <div class="fw-bold fs-5" id="clock">--:--</div>
            </div>
            
            <!-- Right: Profile (Centered over Take Away) -->
            <div class="col-6 d-flex justify-content-center">
                <a href="{{ route('cashier.profile') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                     <div class="text-end" style="line-height: 1.2;">
                         <div class="fw-bold small">{{ Auth::user()->name }}</div>
                         <div class="small text-muted" style="font-size: 0.7rem;">Cashier</div>
                     </div>
                     <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px; font-size: 1.1rem;">
                        <i class="bi bi-person-fill"></i>
                     </div>
                </a>
            </div>
        </div>

        <!-- Floating Customer Card -->
        <div class="bg-white flex-grow-1 shadow-sm rounded-4 d-flex flex-column overflow-hidden mb-3">
            <div class="p-3 bg-white">
                <h6 class="fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="bi bi-cart-dash"></i> Current Order
                </h6>
                
                <div class="mb-2">
                    <input type="text" id="customerName" class="form-control form-control-sm mb-1 rounded-3 bg-light border-0" placeholder="Customer Name" style="font-size: 0.85rem;">
                    
                    <div class="d-flex gap-1 mb-1 p-1 bg-light rounded-3">
                        <input type="radio" class="btn-check" name="orderType" id="dineIn" value="dine_in" checked onchange="toggleTableInput()">
                        <label class="btn btn-sm btn-white text-dark w-50 rounded-3 border-0 shadow-sm fw-bold py-1" style="font-size: 0.75rem;" for="dineIn" id="lblDineIn">Dine In</label>

                        <input type="radio" class="btn-check" name="orderType" id="takeAway" value="take_away" onchange="toggleTableInput()">
                        <label class="btn btn-sm btn-transparent text-muted w-50 rounded-3 border-0 py-1" style="font-size: 0.75rem;" for="takeAway" id="lblTakeAway">Take Away</label>
                    </div>

                    <input type="text" id="tableNumber" class="form-control form-control-sm rounded-3 bg-light border-0" placeholder="Table Number" style="font-size: 0.85rem;">
                </div>
            </div>

            <div class="flex-grow-1 overflow-auto px-3" id="cartItems" style="background-color: #f9f9f9;">
                <!-- Cart Items injected via JS -->
                <div class="text-center text-muted py-5 mt-3" id="emptyCartMsg">
                    <i class="bi bi-basket3 display-4 text-black-50"></i>
                    <p class="mt-2 fw-medium small">No items in cart</p>
                </div>
            </div>

            <div class="p-3 bg-white shadow-lg" style="z-index: 5;">
                <div class="d-flex justify-content-between mb-1 small" style="font-size: 0.8rem;">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold" id="cartSubtotal">Rp0</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small" style="font-size: 0.8rem;">
                    <span class="text-muted">Tax (10%)</span>
                    <span class="fw-bold" id="cartTax">Rp0</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded-3">
                    <span class="fw-bold text-dark small">Total</span>
                    <span class="fw-bolder text-primary" id="cartTotal" style="font-size: 1.1rem;">Rp0</span>
                </div>
                
                <button class="btn btn-dark w-100 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 0.9rem;" onclick="submitOrder()">
                    <i class="bi bi-check-circle-fill"></i> Confirm Order
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Styling for specific components -->
<style>
    .nav-pills .nav-link { color: #555; background-color: white; }
    .nav-pills .nav-link.active { background-color: #C8BFAA; color: #333; font-weight: bold; border-color: #C8BFAA !important; }
    .product-card {
        transition: transform 0.2s;
        cursor: pointer;
        border: none;
    }
    .product-card:hover { transform: translateY(-3px); }
    .product-img {
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
    }
    .cart-item-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
    #cartItems {
        min-height: 0;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Styling toggle for radio buttons
    const styleRadio = () => {
        const dineIn = document.getElementById('dineIn').checked;
        const lblDineIn = document.getElementById('lblDineIn');
        const lblTakeAway = document.getElementById('lblTakeAway');
        
        if(dineIn) {
            lblDineIn.className = 'btn btn-sm btn-white text-dark w-50 rounded-3 shadow-sm fw-bold bg-white';
            lblTakeAway.className = 'btn btn-sm btn-transparent text-muted w-50 rounded-3 border-0';
        } else {
            lblDineIn.className = 'btn btn-sm btn-transparent text-muted w-50 rounded-3 border-0';
            lblTakeAway.className = 'btn btn-sm btn-white text-dark w-50 rounded-3 shadow-sm fw-bold bg-white';
        }
    }

    let cart = {};

    // Event Delegation for Product Click
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.product-card');
        if (card) {
            const id = card.getAttribute('data-id');
            const name = card.getAttribute('data-name');
            const price = parseFloat(card.getAttribute('data-price'));
            const image = card.getAttribute('data-image');
            
            addToCart(id, name, price, image);
        }
    });

    function addToCart(id, name, price, image) {
        // Ensure ID is string key
        id = String(id);
        
        if (cart[id]) {
            cart[id].quantity++;
        } else {
            cart[id] = { id, name, price, image, quantity: 1 };
        }
        renderCart();
    }

    function updateQty(id, change) {
        id = String(id);
        if (cart[id]) {
            cart[id].quantity += change;
            if (cart[id].quantity <= 0) {
                delete cart[id];
            }
            renderCart();
        }
    }

    function renderCart() {
        const cartContainer = document.getElementById('cartItems');
        cartContainer.innerHTML = '';
        
        let subtotal = 0;
        let count = 0;

        if (Object.keys(cart).length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center text-muted py-5 mt-5" id="emptyCartMsg">
                    <i class="bi bi-basket3 display-1 text-black-50"></i>
                    <p class="mt-2 fw-medium">No items in cart</p>
                </div>
            `;
        } else {
            for (const [id, item] of Object.entries(cart)) {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                count += item.quantity;

                const itemHtml = `
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-white rounded-3 shadow-sm border-start border-4 border-dark">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">${item.name}</div>
                            <div class="text-muted small d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                                <span class="bg-light px-2 py-0 rounded border">${item.quantity}x</span>
                                <span>@ Rp${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary" style="font-size: 0.9rem;">Rp${new Intl.NumberFormat('id-ID').format(itemTotal)}</div>
                            <div class="d-flex align-items-center gap-1 mt-1 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:20px;height:20px; font-size: 0.8rem;" onclick="updateQty('${id}', -1)">-</button>
                                <button class="btn btn-sm btn-dark rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:20px;height:20px; font-size: 0.8rem;" onclick="updateQty('${id}', 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
                cartContainer.insertAdjacentHTML('beforeend', itemHtml);
            }
        }

        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        document.getElementById('cartSubtotal').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('cartTax').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(tax);
        document.getElementById('cartTotal').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
    }

    // Search Logic
    document.addEventListener('DOMContentLoaded', function() {
        styleRadio(); // Init style
        
        const searchInput = document.querySelector('input[placeholder="Search menu"]');
        if(searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                const products = document.querySelectorAll('.product-card');
                
                products.forEach(card => {
                    const name = card.getAttribute('data-name').toLowerCase();
                    const container = card.closest('.col-md-3');
                    if(name.includes(term)) {
                        container.style.display = 'block';
                    } else {
                        container.style.display = 'none';
                    }
                });
            });
        }
    });

    function toggleTableInput() {
        const isDineIn = document.getElementById('dineIn').checked;
        const tableInput = document.getElementById('tableNumber');
        if (isDineIn) {
            tableInput.style.display = 'block';
        } else {
            tableInput.style.display = 'none';
            tableInput.value = '';
        }
        styleRadio();
    }

    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('clock').innerText = `${hours}.${minutes}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function submitOrder() {
        const customerName = document.getElementById('customerName').value;
        const tableNumber = document.getElementById('tableNumber').value;
        const orderType = document.querySelector('input[name="orderType"]:checked').value;
        
        if (!customerName) {
            alert('Please enter customer name');
            return;
        }
        if (orderType === 'dine_in' && !tableNumber) {
            alert('Please enter table number for Dine In');
            return;
        }
        if (Object.keys(cart).length === 0) {
            alert('Cart is empty');
            return;
        }

        const items = Object.values(cart).map(item => ({
            id: item.id,
            quantity: item.quantity
        }));
        
        let total = 0;
        Object.values(cart).forEach(i => total += (i.price * i.quantity));
        total = total * 1.1; 

        const data = {
            customer_name: customerName,
            table_number: tableNumber,
            order_type: orderType,
            items: items,
            total_amount: Math.round(total)
        };

        fetch('{{ route('cashier.order.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Pesanan Berhasil!');
                cart = {};
                renderCart();
                document.getElementById('customerName').value = '';
                document.getElementById('tableNumber').value = '';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush
@endsection
