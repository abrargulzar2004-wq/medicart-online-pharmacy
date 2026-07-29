@extends('layouts.app')

@section('title', 'Shop - MediCart')

@section('content')
<div class="grid" style="grid-template-columns: 250px 1fr; gap: 2rem; align-items: start;">
    
    <!-- Sidebar Filters -->
    <aside class="card" style="padding: 1.5rem; position: sticky; top: 100px;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Filters</h3>
        <form method="GET" action="{{ route('shop.index') }}">
            <!-- Search -->
            <div class="form-group">
                <label class="form-label">Search</label>
                <div style="position:relative;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted);"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search medicines..." style="padding-left:35px;">
                </div>
            </div>

            <!-- Categories -->
            <div class="form-group">
                <label class="form-label">Categories</label>
                <div style="display:flex; flex-direction:column; gap:0.5rem; max-height:200px; overflow-y:auto; padding-right:5px;">
                    @foreach($categories as $category)
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Brands -->
            <div class="form-group">
                <label class="form-label">Brands</label>
                <div style="display:flex; flex-direction:column; gap:0.5rem; max-height:200px; overflow-y:auto; padding-right:5px;">
                    @foreach($brands as $brand)
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" name="brands[]" value="{{ $brand->slug }}" {{ in_array($brand->slug, request('brands', [])) ? 'checked' : '' }}>
                            {{ $brand->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary" style="flex:1;">Apply</button>
                <a href="{{ route('shop.index') }}" class="btn btn-outline" style="padding:0.75rem;"><i class="ph ph-arrow-counter-clockwise"></i></a>
            </div>
        </form>
    </aside>

    <!-- Product Grid -->
    <div>
        <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h2>All Products</h2>
            <div style="color:var(--text-muted);">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-3">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            
            <div style="margin-top: 3rem; display:flex; justify-content:center;">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="card" style="padding: 4rem 2rem; text-align: center;">
                <i class="ph ph-package" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-muted);">No products found</h3>
                <p>Try adjusting your search or filters.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Clear Filters</a>
            </div>
        @endif
    </div>
</div>

<style>
/* Custom Pagination Styles */
.pagination { display: flex; list-style: none; gap: 0.5rem; justify-content: center; padding: 0; }
.pagination li a, .pagination li span { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: var(--radius-md); background: white; border: 1px solid #e2e8f0; color: var(--text-main); font-weight: 600; text-decoration: none; transition: var(--transition); }
.pagination li a:hover { background: var(--bg-main); border-color: var(--primary); color: var(--primary); }
.pagination li.active span { background: var(--primary); color: white; border-color: var(--primary); }
</style>
@endsection