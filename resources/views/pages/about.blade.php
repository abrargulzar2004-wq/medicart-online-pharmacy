@extends('layouts.app')

@section('title', 'About Us - MediCart')

@section('content')
<section style="margin-bottom: 3rem;">
    <span class="badge badge-new" style="margin-bottom: 1rem;">About MediCart</span>
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--dark);">Your Trusted Online Pharmacy</h1>
    <p style="font-size: 1.1rem; color: var(--text-muted); max-width: 720px;">
        MediCart is a full-service online pharmacy and healthcare store built to make medicines, vitamins, and wellness products accessible, affordable, and safe for everyone.
    </p>
</section>

<section class="grid grid-cols-2" style="gap: 2rem; margin-bottom: 4rem; align-items: center;">
    <div>
        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=800&auto=format&fit=crop"
             alt="MediCart Pharmacy Team"
             style="width: 100%; border-radius: var(--radius-lg); border: 1px solid var(--border-color); object-fit: cover; max-height: 360px;">
    </div>
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-bottom: 1rem;">Our Mission</h2>
        <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;">
            We believe healthcare should be convenient without compromising safety. MediCart connects customers with authentic pharmaceutical products, licensed pharmacist review for prescription medicines, and reliable home delivery.
        </p>
        <p style="color: var(--text-muted); line-height: 1.7; margin: 0;">
            From everyday vitamins to prescription medications, every product in our catalog is sourced from verified brands and stored under proper conditions.
        </p>
    </div>
</section>

<section style="margin-bottom: 4rem;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h2>What We Offer</h2>
        <p style="color: var(--text-muted);">A complete e-commerce experience designed for healthcare needs.</p>
    </div>
    <div class="grid grid-cols-3" style="gap: 1.5rem;">
        <div class="card" style="padding: 2rem; text-align: center;">
            <i class="ph-fill ph-pill" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 0.5rem;">Wide Product Range</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Prescription medicines, OTC products, vitamins, skincare, baby care, and medical devices.</p>
        </div>
        <div class="card" style="padding: 2rem; text-align: center;">
            <i class="ph-fill ph-prescription" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 0.5rem;">Prescription Verification</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Licensed pharmacists review uploaded prescriptions before restricted medicines are approved.</p>
        </div>
        <div class="card" style="padding: 2rem; text-align: center;">
            <i class="ph-fill ph-truck" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3 style="margin-bottom: 0.5rem;">Fast Delivery</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Secure packaging and timely delivery straight to your doorstep with order tracking.</p>
        </div>
    </div>
</section>

<section class="card" style="padding: 2.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, var(--bg-main) 0%, #E2E8F0 100%);">
    <div class="grid grid-cols-4" style="gap: 2rem; text-align: center;">
        <div>
            <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 0.25rem;">48+</h3>
            <p style="color: var(--text-muted); margin: 0;">Products Listed</p>
        </div>
        <div>
            <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 0.25rem;">6</h3>
            <p style="color: var(--text-muted); margin: 0;">Health Categories</p>
        </div>
        <div>
            <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 0.25rem;">24/7</h3>
            <p style="color: var(--text-muted); margin: 0;">Customer Support</p>
        </div>
        <div>
            <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 0.25rem;">100%</h3>
            <p style="color: var(--text-muted); margin: 0;">Authentic Products</p>
        </div>
    </div>
</section>

<section style="text-align: center; margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem;">Ready to shop?</h2>
    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Browse our catalog or get in touch with our support team.</p>
    <div class="flex gap-4" style="justify-content: center;">
        <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding: 0.875rem 1.75rem;">Shop Now</a>
        <a href="{{ route('contact') }}" class="btn btn-outline" style="padding: 0.875rem 1.75rem;">Contact Us</a>
    </div>
</section>
@endsection
