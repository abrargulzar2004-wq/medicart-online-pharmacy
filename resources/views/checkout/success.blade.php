@extends('layouts.app')
@section('content')
    <div class="card" style="text-align: center; padding: 50px; max-width: 600px; margin: 0 auto;">
        <h1 style="color: var(--success); font-size: 48px; margin: 0 0 20px;">✅</h1>
        <h2>Order Placed Successfully!</h2>
        <p style="font-size: 18px; margin-bottom: 10px;">Thank you for your purchase. Your order number is <strong>{{ $order->order_number }}</strong>.</p>
        
        @if($order->prescription_status === 'Pending')
            <div style="background: #FFFBEB; border: 1px solid #FDE68A; padding: 15px; border-radius: var(--radius-md); margin: 20px 0; color: #92400E;">
                <strong>Note:</strong> Your order contains prescription medicines and is currently under review by our pharmacists.
            </div>
        @endif
        
        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary" style="margin-top: 20px; display: inline-block;">View Order History</a>
    </div>
@endsection