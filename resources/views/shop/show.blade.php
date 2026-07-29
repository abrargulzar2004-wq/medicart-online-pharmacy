@extends('layouts.app')

@section('title', $product->name . ' - MediCart')

@section('content')
<div class="container" style="padding: 2rem 0;">
    
    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('home') }}" style="color: var(--primary);"><i class="ph ph-house"></i> Home</a> / 
        <a href="{{ route('shop.index') }}" style="color: var(--primary);">Shop</a> / 
        @if($product->category)
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="color: var(--primary);">{{ $product->category->name }}</a> / 
        @endif
        <span style="color: var(--text-main);">{{ $product->name }}</span>
    </div>

    <!-- Product Details Section -->
    <div class="grid card" style="grid-template-columns: 1fr 1fr; gap: 4rem; padding: 3rem; margin-bottom: 4rem;">
        
        <!-- Left: Image Gallery -->
        <div>
            <div style="border: 1px solid #e2e8f0; border-radius: var(--radius-lg); padding: 2rem; display:flex; justify-content:center; align-items:center; background: #fff; margin-bottom: 1rem; position:relative;">
                @if($product->requires_prescription)
                    <span class="badge badge-rx" style="position:absolute; top:1rem; left:1rem; font-size:1rem; padding:0.5rem 1rem;"><i class="ph ph-prescription"></i> Prescription Required</span>
                @endif
                <img id="main-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback;">
            </div>
            @if($product->images->count() > 1)
                <div class="flex gap-4" style="overflow-x:auto;">
                    @foreach($product->images as $img)
                        @if($img->isValid(false))
                        @php $thumbUrl = $img->url(); @endphp
                        <div class="thumbnail-wrapper" style="width:80px; height:80px; border: 2px solid {{ $img->is_primary ? 'var(--primary)' : '#e2e8f0' }}; border-radius:var(--radius-md); padding:0.5rem; cursor:pointer;" onclick="document.getElementById('main-image').src = '{{ $thumbUrl }}'">
                            <img src="{{ $thumbUrl }}" style="width:100%; height:100%; object-fit:contain;" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback;">
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Info -->
        <div style="display:flex; flex-direction:column; justify-content:center;">
            <div style="color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">
                {{ $product->brand->name ?? 'Generic' }}
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; line-height:1.2;">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4" style="margin-bottom: 1.5rem;">
                <div class="flex" style="color: #F59E0B; font-size:1.2rem;">
                    <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star-half"></i>
                </div>
                <a href="#reviews" style="color:var(--text-muted); text-decoration:underline;">(128 Reviews)</a>
            </div>

            <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary); margin-bottom: 1.5rem;">
                ${{ number_format($product->price, 2) }}
            </div>

            <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.8;">
                {{ $product->description }}
            </p>

            <div class="flex items-center gap-4" style="margin-bottom: 2rem; padding: 1rem; background: var(--bg-main); border-radius: var(--radius-md);">
                <i class="ph-fill ph-check-circle" style="font-size: 1.5rem; color: var(--success);"></i>
                <div>
                    <strong style="display:block;">Availability</strong>
                    @if($product->stock_quantity > 10)
                        <span style="color: var(--success);">In Stock ({{ $product->stock_quantity }} available)</span>
                    @elseif($product->stock_quantity > 0)
                        <span style="color: var(--warning);">Low Stock ({{ $product->stock_quantity }} left)</span>
                    @else
                        <span style="color: var(--danger);">Out of Stock</span>
                    @endif
                </div>
            </div>

            @auth
                <div class="flex gap-4 items-center">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex:1; display:flex; gap:1rem;">
                        @csrf
                        <div style="border: 1px solid #cbd5e1; border-radius: var(--radius-md); display:flex; overflow:hidden; width: 120px;">
                            <button type="button" onclick="document.getElementById('qty').stepDown()" style="flex:1; border:none; background:#f1f5f9; cursor:pointer; font-size:1.2rem; transition:0.3s;">-</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" style="flex:1.5; border:none; text-align:center; font-weight:bold; font-size:1.1rem; outline:none; -moz-appearance: textfield;">
                            <button type="button" onclick="document.getElementById('qty').stepUp()" style="flex:1; border:none; background:#f1f5f9; cursor:pointer; font-size:1.2rem; transition:0.3s;">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex:1; padding: 1rem; font-size:1.1rem;" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            <i class="ph ph-shopping-cart" style="margin-right:0.5rem;"></i> {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </form>
                    <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="padding: 1rem;" title="Add to Wishlist">
                            <i class="ph ph-heart" style="font-size: 1.2rem;"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex gap-4 items-center">
                    <div style="border: 1px solid #cbd5e1; border-radius: var(--radius-md); display:flex; overflow:hidden; width: 120px; opacity:0.6;">
                        <button type="button" style="flex:1; border:none; background:#f1f5f9; font-size:1.2rem;" disabled>-</button>
                        <input type="number" value="1" style="flex:1.5; border:none; text-align:center; font-weight:bold; font-size:1.1rem;" disabled>
                        <button type="button" style="flex:1; border:none; background:#f1f5f9; font-size:1.2rem;" disabled>+</button>
                    </div>
                    <a href="{{ route('require.auth') }}" class="btn btn-primary" style="flex:1; padding: 1rem; font-size:1.1rem; text-align:center; display:flex; align-items:center; justify-content:center;">
                        <i class="ph ph-shopping-cart" style="margin-right:0.5rem;"></i> Add to Cart
                    </a>
                    <a href="{{ route('require.auth') }}" class="btn btn-outline" style="padding: 1rem; display:flex; align-items:center; justify-content:center;" title="Add to Wishlist">
                        <i class="ph ph-heart" style="font-size: 1.2rem;"></i>
                    </a>
                </div>
            @endauth
            
            <div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; display:flex; flex-direction:column; gap:0.5rem; color:var(--text-muted); font-size:0.95rem;">
                <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                <p><strong>Categories:</strong> <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" style="color:var(--primary);">{{ $product->category->name }}</a></p>
                <p><strong>Tags:</strong> Health, Pharmacy, {{ $product->brand->name ?? 'Generic' }}</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Hide up/down arrows in number input */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection