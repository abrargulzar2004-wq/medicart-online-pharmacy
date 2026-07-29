@props([
    'product',
    'variant' => 'default',
])

@if($product->hasValidImage())
@php
    $placeholderUrl = asset('images/product-placeholder.svg');
    $imageUrl = $product->imageUrl();
@endphp

<div {{ $attributes->merge(['class' => 'card product-card']) }}>
    <a href="{{ route('shop.show', $product->slug) }}" class="product-img-wrapper">
        <div class="product-img-placeholder" aria-hidden="true">
            <i class="ph ph-pill"></i>
        </div>

        <img
            src="{{ $imageUrl }}"
            alt="{{ $product->name }}"
            class="product-img"
            loading="lazy"
            decoding="async"
            data-fallback="{{ $placeholderUrl }}"
            onerror="this.onerror=null;this.src=this.dataset.fallback;this.classList.add('is-error');"
        >

        <div class="product-badges">
            @if($product->requires_prescription)
                <span class="badge badge-rx">Rx Required</span>
            @endif
            @if($product->is_new_arrival)
                <span class="badge badge-new">New</span>
            @endif
        </div>
    </a>

    <div class="product-info">
        <span class="product-category">{{ $product->category->name ?? 'Medicine' }}</span>
        <a href="{{ route('shop.show', $product->slug) }}">
            <h3 class="product-title">{{ $product->name }}</h3>
        </a>
        <div class="product-brand">
            By <span>{{ $product->brand->name ?? 'Generic' }}</span>
        </div>

        <div class="product-price">${{ number_format($product->price, 2) }}</div>

        @if($variant === 'wishlist')
            <div class="product-actions product-actions--split">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i class="ph ph-shopping-cart"></i> Cart
                    </button>
                </form>
                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline product-remove-btn" title="Remove from Wishlist">
                        <i class="ph-fill ph-trash"></i>
                    </button>
                </form>
            </div>
        @else
            <div class="product-actions">
                @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="ph ph-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                @else
                    <a href="{{ route('require.auth') }}" class="btn btn-primary" style="width:100%; text-align:center;">
                        <i class="ph ph-shopping-cart"></i> Add to Cart
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>
@endif
