@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Order Management</h1>
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex; gap:10px;">
            <select name="status" class="form-control" style="padding:8px; width:auto;">
                <option value="">All Orders</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="admin-btn-primary">Filter</button>
        </form>
    </div>
    
    <table class="admin-card" style="width:100%; border-collapse:collapse; margin-top:20px; padding:0;">
        <thead>
            <tr style="background:#0F172A; color:#fff;">
                <th style="padding:15px; text-align:left;">Order ID</th>
                <th style="padding:15px; text-align:left;">Customer</th>
                <th style="padding:15px; text-align:left;">Total</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:left;">Prescription</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr style="border-bottom:1px solid #E2E8F0;">
                <td style="padding:15px;">{{ $order->order_number }}</td>
                <td style="padding:15px;">{{ $order->user->name ?? 'Guest' }}</td>
                <td style="padding:15px;">${{ $order->final_amount }}</td>
                <td style="padding:15px;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight:600; background: {{ $order->order_status == 'Pending' ? '#D97706' : ($order->order_status == 'Delivered' ? '#059669' : '#2563EB') }}; color:#fff;">
                        {{ $order->order_status }}
                    </span>
                </td>
                <td style="padding:15px;">
                    @if($order->prescription_status)
                        <span style="color: {{ $order->prescription_status == 'Approved' ? '#059669' : ($order->prescription_status == 'Rejected' ? '#DC2626' : '#D97706') }}; font-weight:600;">{{ $order->prescription_status }}</span>
                    @else
                        <span class="admin-text-muted">None</span>
                    @endif
                </td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn-primary" style="padding:6px 12px; font-size:14px; background:#0F172A; border-color:#0F172A;">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">{{ $orders->links() }}</div>
@endsection
