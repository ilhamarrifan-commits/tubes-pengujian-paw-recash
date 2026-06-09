<div class="col-md-3 col-sm-6">
    <div class="card product-card p-2 h-100" 
         data-id="{{ $product->id }}" 
         data-name="{{ $product->name }}" 
         data-price="{{ $product->price }}" 
         data-image="{{ asset('storage/' . $product->image) }}">
        <div class="position-relative">
             @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
            @else
            <div class="bg-light product-img d-flex align-items-center justify-content-center text-muted">No Image</div>
            @endif
            @if(!$product->is_available)
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white fw-bold rounded">Unavailable</div>
            @endif
        </div>
        <div class="card-body p-2 text-center">
            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
            <div class="text-primary fw-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
            <div class="small text-muted">{{ $product->stock }} left</div>
        </div>
    </div>
</div>
