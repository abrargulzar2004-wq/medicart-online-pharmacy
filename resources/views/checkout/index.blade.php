@extends('layouts.app')

@section('title', 'Secure Checkout - MediCart')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <h1 style="margin-bottom: 2rem; font-size: 2.5rem; text-align:center;">Secure Checkout</h1>
    
    <div class="grid" style="grid-template-columns: 2fr 1.2fr; gap: 3rem; align-items: start;">
        
        <!-- Left: Checkout Form -->
        <div class="card" style="padding: 2.5rem;">
            <form method="POST" action="{{ route('checkout.process') }}" enctype="multipart/form-data">
                @csrf
                
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-map-pin" style="color:var(--primary);"></i> Shipping Details</h3>
                <div class="form-group">
                    <label class="form-label">Full Shipping Address</label>
                    <textarea name="shipping_address" class="form-control" required rows="4" placeholder="Enter your full home address, including apartment number and zip code..." style="background:#f8fafc;"></textarea>
                </div>
                
                <hr style="border:0; border-top:1px solid #e2e8f0; margin: 2rem 0;">
                
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-credit-card" style="color:var(--primary);"></i> Payment Method</h3>
                <div class="grid grid-cols-2" style="gap: 1rem; margin-bottom: 2rem;">
                    <label style="border: 2px solid var(--primary); padding: 1.5rem; border-radius: var(--radius-md); cursor:pointer; display:flex; flex-direction:column; align-items:center; gap:0.5rem; text-align:center; background:#EFF6FF;">
                        <input type="radio" name="payment_method" value="COD" checked style="display:none;">
                        <i class="ph ph-money" style="font-size:2rem; color:var(--primary);"></i>
                        <span style="font-weight:600;">Cash on Delivery</span>
                    </label>
                    <label style="border: 2px solid #e2e8f0; padding: 1.5rem; border-radius: var(--radius-md); cursor:pointer; display:flex; flex-direction:column; align-items:center; gap:0.5rem; text-align:center; transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <input type="radio" name="payment_method" value="Bank Transfer" style="display:none;">
                        <i class="ph ph-bank" style="font-size:2rem; color:var(--text-muted);"></i>
                        <span style="font-weight:600; color:var(--text-muted);">Bank Transfer</span>
                    </label>
                </div>

                @if($requiresPrescription)
                    <hr style="border:0; border-top:1px solid #e2e8f0; margin: 2rem 0;">
                    <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; display:flex; align-items:center; gap:0.5rem; color:#B45309;"><i class="ph-fill ph-prescription"></i> Prescription Required</h3>
                    <div style="background: #FEF3C7; border: 1px solid #FDE68A; padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
                        <p style="font-size: 0.95rem; color: #92400E; margin-bottom: 1rem;">Your cart contains restricted medicines. Please upload a valid doctor's prescription (PDF, JPG, PNG). A licensed pharmacist will review it before approving the order.</p>
                        <div style="background: #fff; padding: 1rem; border-radius: var(--radius-sm); border: 1px dashed #D97706;">
                            <input type="file" name="prescription" required accept=".pdf,image/*" style="width: 100%;">
                        </div>
                    </div>
                @endif
                
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.25rem; padding: 1.25rem; margin-top: 1rem;">
                    Place Order & Pay <i class="ph ph-lock-key" style="margin-left:0.5rem;"></i>
                </button>
            </form>
        </div>
        
        <!-- Right: Order Summary -->
        <div class="card" style="padding: 2rem; position: sticky; top: 100px;">
            <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; font-size: 1.25rem;">Order Review</h3>
            
            <div style="max-height: 300px; overflow-y:auto; margin-bottom: 1.5rem; padding-right:10px;">
                @foreach($cart->items as $item)
                    <div class="flex items-center gap-3" style="margin-bottom: 1rem; font-size: 0.95rem;">
                        <div style="width: 50px; height: 50px; background:#f8fafc; border-radius:var(--radius-sm); padding:0.25rem; flex-shrink:0;">
                            <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}" style="width:100%; height:100%; object-fit:contain;" data-fallback="{{ asset('images/product-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.dataset.fallback;">
                        </div>
                        <div style="flex-grow:1; line-height:1.2;">
                            <strong style="display:block; color:var(--dark);">{{ $item->product->name }}</strong>
                            <span style="color:var(--text-muted); font-size:0.8rem;">Qty: {{ $item->quantity }}</span>
                        </div>
                        <div style="font-weight:600; color:var(--dark);">
                            ${{ number_format($item->price * $item->quantity, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
            
            <div class="flex justify-between items-center" style="margin-bottom: 1rem; font-size: 1.1rem;">
                <span style="color: var(--text-muted);">Subtotal</span>
                <strong style="color: var(--dark);">${{ number_format($total, 2) }}</strong>
            </div>
            
            <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; font-size: 1.1rem;">
                <span style="color: var(--text-muted);">Shipping</span>
                <strong style="color: var(--success);">Free</strong>
            </div>
            
            <div style="border-top: 2px dashed #e2e8f0; margin: 1.5rem 0;"></div>
            
            <div class="flex justify-between items-center" style="margin-bottom: 1rem;">
                <span style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Total</span>
                <span style="font-size: 2rem; font-weight: 800; color: var(--primary);">${{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection