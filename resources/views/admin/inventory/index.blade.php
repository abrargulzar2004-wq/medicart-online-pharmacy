@extends('admin.layout')
@section('content')
    <h1>Inventory Management</h1>
    <table class="admin-card" style="width:100%; border-collapse:collapse; margin-top:20px; padding:0;">
        <thead>
            <tr style="background:#0F172A; color:#fff;">
                <th style="padding:15px; text-align:left;">Product Name</th>
                <th style="padding:15px; text-align:left;">SKU</th>
                <th style="padding:15px; text-align:left;">Stock Status</th>
                <th style="padding:15px; text-align:left;">Stock Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr style="border-bottom:1px solid #E2E8F0;">
                <td style="padding:15px;">{{ $product->name }}</td>
                <td style="padding:15px;">{{ $product->sku }}</td>
                <td style="padding:15px;">
                    @if($product->stock_quantity == 0)
                        <span style="color:#DC2626; font-weight:600;">Out of Stock</span>
                    @elseif($product->stock_quantity <= 10)
                        <span style="color:#D97706; font-weight:600;">Low Stock</span>
                    @else
                        <span style="color:#059669; font-weight:600;">In Stock</span>
                    @endif
                </td>
                <td style="padding:15px;">
                    <form action="{{ route('admin.inventory.update', $product->id) }}" method="POST" style="display:flex; align-items:center;">
                        @csrf
                        <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0" style="width:70px; padding:5px; margin-right:10px;">
                        <button type="submit" class="admin-btn-primary" style="padding:5px 10px; font-size:14px;">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
