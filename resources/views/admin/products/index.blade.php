@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" class="admin-btn-primary">+ Add Product</a>
    </div>
    
    <div class="admin-card" style="margin-top:20px;">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:15px;">
            <input type="text" name="search" placeholder="Search by name or SKU..." value="{{ request('search') }}" style="padding:8px; border:1px solid #ccc; flex-grow:1;">
            <select name="category" style="padding:8px; border:1px solid #ccc;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="admin-btn-primary">Filter</button>
            <a href="{{ route('admin.products.index') }}" style="background:#DC2626; color:#fff; padding:8px 15px; text-decoration:none; text-align:center; border-radius:0.375rem;">Clear</a>
        </form>
    </div>

    <table class="admin-card" style="width:100%; border-collapse:collapse; margin-top:20px; padding:0;">
        <thead>
            <tr style="background:#0F172A; color:#fff;">
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Category</th>
                <th style="padding:15px; text-align:left;">Price</th>
                <th style="padding:15px; text-align:left;">Stock</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="border-bottom:1px solid #E2E8F0;">
                <td style="padding:15px;">{{ $product->name }}</td>
                <td style="padding:15px;">{{ $product->category->name ?? 'N/A' }}</td>
                <td style="padding:15px;">${{ $product->price }}</td>
                <td style="padding:15px;">{{ $product->stock_quantity }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-link" style="margin-right:10px;">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="color:#DC2626; background:none; border:none; cursor:pointer; font-weight:600;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:15px; text-align:center;">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
@endsection