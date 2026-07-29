@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Order Details: {{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="admin-link">&larr; Back to Orders</a>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:20px;">
        <!-- Left Column: Details -->
        <div>
            <div class="admin-card" style="margin-bottom:30px;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Customer Information</h3>
                <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p><strong>Shipping Address:</strong><br>{{ nl2br($order->shipping_address) }}</p>
            </div>

            <div class="admin-card">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Order Items</h3>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="text-align:left; border-bottom:2px solid #E2E8F0;">
                            <th style="padding:10px 0;">Product</th>
                            <th style="padding:10px 0;">Qty</th>
                            <th style="padding:10px 0;">Price</th>
                            <th style="padding:10px 0;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr style="border-bottom:1px solid #E2E8F0;">
                            <td style="padding:10px 0;">{{ $item->product->name ?? 'Deleted Product' }}
                                @if($item->product && $item->product->requires_prescription) <span style="color:#DC2626; font-weight:bold; font-size:12px;">(Rx)</span> @endif
                            </td>
                            <td style="padding:10px 0;">{{ $item->quantity }}</td>
                            <td style="padding:10px 0;">${{ $item->price }}</td>
                            <td style="padding:10px 0;">${{ $item->price * $item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="text-align:right; margin-top:20px; font-size:20px;">
                    <strong>Total: <span style="color:#059669;">${{ $order->final_amount }}</span></strong>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions -->
        <div>
            @if($order->prescription_status)
            <div class="admin-card" style="margin-bottom:30px; border-left:4px solid #D97706;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Prescription Review</h3>
                <p>Status: <strong>{{ $order->prescription_status }}</strong></p>
                <div style="margin: 15px 0;">
                    <a href="{{ asset('storage/' . $order->prescription_path) }}" target="_blank" class="admin-btn-primary" style="display:inline-block; padding:10px 20px;">View Uploaded Prescription</a>
                </div>
                
                <form action="{{ route('admin.orders.prescription.update', $order) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Action</label>
                        <select name="prescription_status" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="Pending" {{ $order->prescription_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $order->prescription_status == 'Approved' ? 'selected' : '' }}>Approve</option>
                            <option value="Rejected" {{ $order->prescription_status == 'Rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Remarks (Optional, required if rejecting)</label>
                        <textarea name="prescription_remarks" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">{{ $order->prescription_remarks }}</textarea>
                    </div>
                    <button type="submit" class="admin-btn-primary" style="background:#D97706; border-color:#D97706; padding:10px 20px;">Update Prescription Status</button>
                </form>
            </div>
            @endif

            <div class="admin-card">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Update Order Status</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Payment Status</label>
                        <select name="payment_status" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Order Status</label>
                        <select name="order_status" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="Pending" {{ $order->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ $order->order_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ $order->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ $order->order_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="admin-btn-success" style="padding:10px 20px; width:100%;">Update Order</button>
                </form>
            </div>
        </div>
    </div>
@endsection