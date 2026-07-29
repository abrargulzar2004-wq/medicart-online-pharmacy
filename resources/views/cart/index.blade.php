@extends('layouts.app')

@section('title', 'Shopping Cart - MediCart')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h1 style="margin: 0; font-size: 2.5rem;">Shopping Cart</h1>
        <a href="{{ route('shop.index') }}" style="color:var(--text-muted); font-weight:600;"><i class="ph ph-arrow-left"></i> Continue Shopping</a>
    </div>
    
    @if(empty($cartItems))
        <div class="card" style="text-align:center; padding: 5rem 2rem;">
            <i class="ph ph-shopping-cart" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h2 style="color: var(--dark); margin-bottom: 1rem;">Your cart is empty.</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Looks like you haven't added any medicines or health products to your cart yet.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Shop Medicines</a>
        </div>
    @else
        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
            
            <!-- Cart Items -->
            <div class="card" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--bg-main); border-bottom: 1px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 1.5rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em;">Product Details</th>
                            <th style="padding: 1.5rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; text-align: center;">Price</th>
                            <th style="padding: 1.5rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; text-align: center;">Qty</th>
                            <th style="padding: 1.5rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 1.5rem; display: flex; align-items: center; gap: 1.5rem;">
                                    <div style="width: 80px; height: 80px; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 0.5rem; background: #fff;">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: contain;" class="product-img" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                                    </div>
                                    <div>
                                        <h3 style="font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $item['name'] }}</h3>
                                    </div>
                                </td>
                                <td style="padding: 1.5rem; font-weight: 600; text-align: center;">${{ number_format($item['price'], 2) }}</td>
                                <td style="padding: 1.5rem; text-align: center;">
                                    <span style="display:inline-block; padding:0.5rem 1rem; background:var(--bg-main); border-radius:var(--radius-sm); font-weight:bold;">{{ $item['quantity'] }}</span>
                                </td>
                                <td style="padding: 1.5rem; font-weight: 800; color: var(--primary); text-align: right; font-size: 1.1rem;">${{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Order Summary -->
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; font-size: 1.25rem;">Order Summary</h3>
                
                <div class="flex justify-between items-center" style="margin-bottom: 1rem; font-size: 1.1rem;">
                    <span style="color: var(--text-muted);">Subtotal</span>
                    <strong style="color: var(--dark);">${{ number_format($total, 2) }}</strong>
                </div>
                
                <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; font-size: 1.1rem;">
                    <span style="color: var(--text-muted);">Shipping</span>
                    <strong style="color: var(--dark);">Calculated at checkout</strong>
                </div>

                @if($requiresPrescription)
                    <div class="alert alert-warning" style="background: #FEF3C7; color: #B45309; border-left: 4px solid #F59E0B; padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <div class="flex gap-2 items-start">
                            <i class="ph-fill ph-warning-circle" style="font-size:1.25rem; margin-top:2px;"></i>
                            <div>
                                <strong>Prescription Needed</strong><br>
                                One or more items require a prescription. You'll upload it during checkout.
                            </div>
                        </div>
                    </div>
                @endif
                
                <div style="border-top: 2px dashed #e2e8f0; margin: 1.5rem 0;"></div>
                
                <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Total Amount</span>
                    <span style="font-size: 2rem; font-weight: 800; color: var(--primary);">${{ number_format($total, 2) }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="display: flex; text-align: center; width: 100%; padding: 1.25rem; font-size: 1.1rem; justify-content:center;">Proceed to Checkout <i class="ph ph-arrow-right" style="margin-left:0.5rem;"></i></a>
                
                <div style="margin-top: 1.5rem; text-align: center; display: flex; justify-content: center; gap: 1rem; color: #cbd5e1; font-size: 2rem;">
                    <i class="ph-fill ph-stripe-logo"></i>
                    <i class="ph-fill ph-paypal-logo"></i>
                    <i class="ph ph-lock-key"></i>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection