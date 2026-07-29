@extends('admin.layout')
@section('content')
    <h1>Edit Product</h1>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="admin-card" style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        @csrf @method('PUT')
        <div style="grid-column: 1 / -1;"><label>Name</label><input type="text" name="name" value="{{ $product->name }}" required style="width:100%; padding:10px;"></div>
        <div><label>Price</label><input type="number" step="0.01" name="price" value="{{ $product->price }}" required style="width:100%; padding:10px;"></div>
        <div><label>Stock Quantity</label><input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" required style="width:100%; padding:10px;"></div>
        <div style="grid-column: 1 / -1; display:flex; gap:20px;">
            <label><input type="checkbox" name="requires_prescription" {{ $product->requires_prescription ? 'checked' : '' }}> Prescription</label>
            <label><input type="checkbox" name="status" {{ $product->status ? 'checked' : '' }}> Active</label>
        </div>
        <button type="submit" class="admin-btn-primary" style="grid-column: 1 / -1; padding:10px 20px;">Update Product</button>
    </form>
@endsection