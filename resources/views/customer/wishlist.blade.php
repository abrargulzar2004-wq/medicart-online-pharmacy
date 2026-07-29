@extends('layouts.app')

@section('title', 'My Wishlist - MediCart')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h1 style="margin: 0; font-size: 2.5rem;">My Wishlist</h1>
        <a href="{{ route('shop.index') }}" style="color:var(--text-muted); font-weight:600;"><i class="ph ph-arrow-left"></i> Continue Shopping</a>
    </div>
    
    @if($wishlists->isEmpty())
        <div class="card" style="text-align:center; padding: 5rem 2rem;">
            <i class="ph ph-heart-break" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h2 style="color: var(--dark); margin-bottom: 1rem;">Your wishlist is empty.</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Save items you love to your wishlist and review them anytime.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Shop Medicines</a>
        </div>
    @else
        <div class="grid grid-cols-4" style="gap: 2rem;">
            @foreach($wishlists as $item)
                @if($item->product)
                    <x-product-card :product="$item->product" variant="wishlist" />
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
