@extends('admin.layout')
@section('content')
    <h1>Add Product</h1>
    @if($errors->any())
        <div class="alert-danger" style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:15px;">
            <ul style="margin:0;">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-card" style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        @csrf
        <div style="grid-column: 1 / -1;"><label>Name</label><input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:10px;"></div>
        <div>
            <label>Category</label>
            <select name="category_id" style="width:100%; padding:10px;">
                @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label>Brand</label>
            <select name="brand_id" style="width:100%; padding:10px;">
                <option value="">None</option>
                @foreach($brands as $brand) <option value="{{ $brand->id }}">{{ $brand->name }}</option> @endforeach
            </select>
        </div>
        <div style="grid-column: 1 / -1;"><label>Description</label><textarea name="description" style="width:100%; padding:10px;" rows="4">{{ old('description') }}</textarea></div>
        <div><label>Price</label><input type="number" step="0.01" name="price" value="{{ old('price') }}" required style="width:100%; padding:10px;"></div>
        <div><label>Stock Quantity</label><input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" required style="width:100%; padding:10px;"></div>
        <div><label>SKU</label><input type="text" name="sku" value="{{ old('sku') }}" required style="width:100%; padding:10px;"></div>
        <div><label>Batch Number</label><input type="text" name="batch_number" value="{{ old('batch_number') }}" style="width:100%; padding:10px;"></div>
        
        <div style="grid-column: 1 / -1;">
            <label>Product Images (Multiple)</label>
            <input type="file" name="images[]" multiple accept="image/*" style="width:100%; padding:10px; border:1px solid #ccc;">
        </div>

        <div style="grid-column: 1 / -1; display:flex; gap:20px; flex-wrap:wrap;">
            <label><input type="checkbox" name="requires_prescription"> Requires Prescription</label>
            <label><input type="checkbox" name="is_featured"> Featured</label>
            <label><input type="checkbox" name="is_new_arrival"> New Arrival</label>
            <label><input type="checkbox" name="is_best_seller"> Best Seller</label>
            <label><input type="checkbox" name="status" checked> Active</label>
        </div>
        <button type="submit" class="admin-btn-primary" style="grid-column: 1 / -1; padding:10px 20px; font-size:16px;">Save Product</button>
    </form>
@endsection