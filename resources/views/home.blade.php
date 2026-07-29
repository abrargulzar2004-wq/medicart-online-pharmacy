@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, var(--bg-main) 0%, #E2E8F0 100%); border-radius: var(--radius-lg); padding: 4rem 2rem; margin-bottom: 4rem; display:flex; align-items:center; gap:2rem;">
    <div style="flex:1;">
        <span class="badge badge-new" style="margin-bottom:1rem;">Fast & Secure</span>
        <h1 style="font-size:3rem; margin-bottom:1.5rem; color:var(--dark);">Your Health, Delivered<br><span style="color:var(--primary);">Right to Your Door.</span></h1>
        <p style="font-size:1.1rem; color:var(--text-muted); margin-bottom:2rem; max-width:80%;">Authentic medicines, professional advice, and secure delivery. Experience the future of online pharmacies with MediCart.</p>
        <div class="flex gap-4">
            <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding:1rem 2rem; font-size:1.1rem;">Shop Medicines</a>
            <a href="#categories" class="btn btn-outline" style="padding:1rem 2rem; font-size:1.1rem;">Browse Categories</a>
        </div>
    </div>
    <div style="flex:1; display:flex; justify-content:center;">
        <!-- Generated Image placeholder for Hero -->
        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=800&auto=format&fit=crop" alt="Pharmacy Delivery" style="border-radius:var(--radius-lg); border:1px solid var(--border-color); width:100%; max-height:400px; object-fit:cover;">
    </div>
</section>

<!-- Shop by Category -->
<section id="categories" style="margin-bottom: 4rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Shop by Category</h2>
        <a href="{{ route('shop.index') }}" style="color:var(--primary); font-weight:600; display:flex; align-items:center; gap:0.25rem;">View All <i class="ph ph-arrow-right"></i></a>
    </div>
    <div class="grid grid-cols-4" style="gap:1.5rem;">
        @foreach(\App\Models\Category::take(8)->get() as $category)
            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="card" style="padding:2rem 1.5rem; text-align:center; transition:var(--transition); text-decoration:none;">
                <div style="width:60px; height:60px; background:var(--primary); color:white; border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:1.5rem;">
                    <i class="ph {{ $category->icon ?? 'ph-pill' }}"></i>
                </div>
                <h3 style="font-size:1.1rem; margin-bottom:0.5rem;">{{ $category->name }}</h3>
                <p style="font-size:0.85rem; color:var(--text-muted); margin:0;">Explore Products</p>
            </a>
        @endforeach
    </div>
</section>

<!-- Featured Products -->
<section style="margin-bottom: 4rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Featured Medicines</h2>
    </div>
    <div class="grid grid-cols-4">
        @forelse($featuredProducts as $product)
            <x-product-card :product="$product" />
        @empty
            <p style="grid-column: 1 / -1; color: var(--text-muted);">No featured products available right now.</p>
        @endforelse
    </div>
</section>

<!-- Why Choose Us -->
<section style="background:var(--bg-white); border-radius:var(--radius-lg); padding:4rem 2rem; margin-bottom:4rem; border:1px solid var(--border-color);">
    <div style="text-align:center; margin-bottom:3rem;">
        <h2>Why Choose MediCart</h2>
        <p style="color:var(--text-muted);">We prioritize your health, safety, and convenience above all else.</p>
    </div>
    <div class="grid grid-cols-4" style="gap:2rem; text-align:center;">
        <div>
            <i class="ph-fill ph-truck" style="font-size:3rem; color:var(--primary); margin-bottom:1rem;"></i>
            <h4 style="font-size:1.1rem;">Fast & Free Delivery</h4>
            <p style="font-size:0.9rem; color:var(--text-muted);">On orders over $50</p>
        </div>
        <div>
            <i class="ph-fill ph-shield-check" style="font-size:3rem; color:var(--primary); margin-bottom:1rem;"></i>
            <h4 style="font-size:1.1rem;">100% Authentic</h4>
            <p style="font-size:0.9rem; color:var(--text-muted);">Sourced directly from brands</p>
        </div>
        <div>
            <i class="ph-fill ph-lock-key" style="font-size:3rem; color:var(--primary); margin-bottom:1rem;"></i>
            <h4 style="font-size:1.1rem;">Secure Payments</h4>
            <p style="font-size:0.9rem; color:var(--text-muted);">256-bit SSL encryption</p>
        </div>
        <div>
            <i class="ph-fill ph-headset" style="font-size:3rem; color:var(--primary); margin-bottom:1rem;"></i>
            <h4 style="font-size:1.1rem;">24/7 Support</h4>
            <p style="font-size:0.9rem; color:var(--text-muted);">Pharmacists on standby</p>
        </div>
    </div>
</section>

@endsection