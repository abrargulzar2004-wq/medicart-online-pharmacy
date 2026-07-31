<?php
$controllersDir = __DIR__ . '/app/Http/Controllers/Admin';
$viewsDir = __DIR__ . '/resources/views/admin';

// 1. Update AdminController to use real data for the chart
$adminController = <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        \$totalRevenue = Order::where('payment_status', 'paid')->sum('final_amount');
        \$totalOrders = Order::count();
        \$totalCustomers = User::where('role', 'customer')->count();
        \$totalProducts = Product::count();
        
        \$pendingOrders = Order::where('order_status', 'Pending')->count();
        \$completedOrders = Order::where('order_status', 'Delivered')->count();
        
        \$lowStockProducts = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        \$outOfStockProducts = Product::where('stock_quantity', 0)->count();

        // Chart Data: Group Revenue by Month for the current year
        \$salesData = Order::select(
                DB::raw('sum(final_amount) as total'), 
                DB::raw('MONTH(created_at) as month')
            )
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        \$chartLabels = [];
        \$chartValues = [];
        \$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Initialize with 0s
        for (\$i = 1; \$i <= 12; \$i++) {
            \$chartLabels[] = \$months[\$i - 1];
            \$chartValues[\$i] = 0;
        }
        
        foreach (\$salesData as \$data) {
            \$chartValues[\$data->month] = \$data->total;
        }
        
        \$chartValues = array_values(\$chartValues);

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'pendingOrders', 'completedOrders', 'lowStockProducts', 'outOfStockProducts',
            'chartLabels', 'chartValues'
        ));
    }
}
PHP;
file_put_contents($controllersDir . '/AdminController.php', $adminController);

// Update Dashboard View for Chart
$dashboard = <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Dashboard Overview</h1>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #3498DB;">
            <h3 style="margin:0 0 10px; color:#7f8c8d; font-size:16px;">Total Revenue</h3>
            <p style="margin:0; font-size: 28px; font-weight: bold; color: #2c3e50;">$\${{ number_format(\$totalRevenue, 2) }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #2ECC71;">
            <h3 style="margin:0 0 10px; color:#7f8c8d; font-size:16px;">Total Orders</h3>
            <p style="margin:0; font-size: 28px; font-weight: bold; color: #2c3e50;">{{ \$totalOrders }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #9B59B6;">
            <h3 style="margin:0 0 10px; color:#7f8c8d; font-size:16px;">Total Customers</h3>
            <p style="margin:0; font-size: 28px; font-weight: bold; color: #2c3e50;">{{ \$totalCustomers }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #F1C40F;">
            <h3 style="margin:0 0 10px; color:#7f8c8d; font-size:16px;">Total Products</h3>
            <p style="margin:0; font-size: 28px; font-weight: bold; color: #2c3e50;">{{ \$totalProducts }}</p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <canvas id="salesChart"></canvas>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h3>Order Status</h3>
            <p>Pending: <span style="color:#E67E22; font-weight:bold;">{{ \$pendingOrders }}</span></p>
            <p>Completed: <span style="color:#2ECC71; font-weight:bold;">{{ \$completedOrders }}</span></p>
            <br>
            <h3>Inventory Alerts</h3>
            <p>Low Stock: <span style="color:#F39C12; font-weight:bold;">{{ \$lowStockProducts }}</span></p>
            <p>Out of Stock: <span style="color:#E74C3C; font-weight:bold;">{{ \$outOfStockProducts }}</span></p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(\$chartLabels) !!},
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: {!! json_encode(\$chartValues) !!},
                backgroundColor: '#3498DB'
            }]
        }
    });
</script>
@endpush
HTML;
file_put_contents($viewsDir . '/dashboard.blade.php', $dashboard);

// 2. Update ProductController for image upload and pagination/search
$productController = <<<PHP
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request \$request) {
        \$query = Product::with(['category', 'brand']);
        
        if (\$request->filled('search')) {
            \$query->where('name', 'like', '%' . \$request->search . '%')
                  ->orWhere('sku', 'like', '%' . \$request->search . '%');
        }
        
        if (\$request->filled('category')) {
            \$query->where('category_id', \$request->category);
        }
        
        \$products = \$query->paginate(10);
        \$categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create() {
        \$categories = Category::all();
        \$brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        \$data = \$request->all();
        \$data['slug'] = Str::slug(\$request->name) . '-' . time();
        \$data['requires_prescription'] = \$request->has('requires_prescription');
        \$data['status'] = \$request->has('status');
        \$data['is_featured'] = \$request->has('is_featured');
        \$data['is_new_arrival'] = \$request->has('is_new_arrival');
        \$data['is_best_seller'] = \$request->has('is_best_seller');
        
        \$product = Product::create(\$data);
        
        // Handle Image Upload
        if (\$request->hasFile('images')) {
            foreach (\$request->file('images') as \$index => \$image) {
                \$path = \$image->store('products', 'public');
                ProductImage::create([
                    'product_id' => \$product->id,
                    'image_path' => \$path,
                    'is_primary' => \$index === 0 ? 1 : 0
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product \$product) {
        \$categories = Category::all();
        \$brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request \$request, Product \$product) {
        \$data = \$request->all();
        \$data['requires_prescription'] = \$request->has('requires_prescription');
        \$data['status'] = \$request->has('status');
        \$data['is_featured'] = \$request->has('is_featured');
        \$data['is_new_arrival'] = \$request->has('is_new_arrival');
        \$data['is_best_seller'] = \$request->has('is_best_seller');
        
        \$product->update(\$data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product \$product) {
        \$product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
PHP;
file_put_contents($controllersDir . '/ProductController.php', $productController);

// Update Product Index View (Search & Pagination)
file_put_contents($viewsDir . '/products/index.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" style="background:#18BC9C; color:#fff; padding:10px 15px; text-decoration:none; border-radius:4px;">+ Add Product</a>
    </div>
    
    <div style="background:#fff; padding:20px; margin-top:20px; border-radius:4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:15px;">
            <input type="text" name="search" placeholder="Search by name or SKU..." value="{{ request('search') }}" style="padding:8px; border:1px solid #ccc; flex-grow:1;">
            <select name="category" style="padding:8px; border:1px solid #ccc;">
                <option value="">All Categories</option>
                @foreach(\$categories as \$category)
                    <option value="{{ \$category->id }}" {{ request('category') == \$category->id ? 'selected' : '' }}>{{ \$category->name }}</option>
                @endforeach
            </select>
            <button type="submit" style="background:#3498DB; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Filter</button>
            <a href="{{ route('admin.products.index') }}" style="background:#E74C3C; color:#fff; padding:8px 15px; text-decoration:none; text-align:center;">Clear</a>
        </form>
    </div>

    <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:20px;">
        <thead>
            <tr style="background:#34495E; color:#fff;">
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Category</th>
                <th style="padding:15px; text-align:left;">Price</th>
                <th style="padding:15px; text-align:left;">Stock</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\$products as \$product)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$product->name }}</td>
                <td style="padding:15px;">{{ \$product->category->name ?? 'N/A' }}</td>
                <td style="padding:15px;">\${{ \$product->price }}</td>
                <td style="padding:15px;">{{ \$product->stock_quantity }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.products.edit', \$product) }}" style="color:#3498DB; margin-right:10px;">Edit</a>
                    <form action="{{ route('admin.products.destroy', \$product) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="color:#E74C3C; background:none; border:none; cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:15px; text-align:center;">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ \$products->links() }}
    </div>
@endsection
HTML
);

// Update Product Create View (enctype and file input)
file_put_contents($viewsDir . '/products/create.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Add Product</h1>
    @if(\$errors->any())
        <div class="alert-danger" style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:15px;">
            <ul style="margin:0;">
                @foreach(\$errors->all() as \$error) <li>{{ \$error }}</li> @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="background:#fff; padding:30px; display:grid; grid-template-columns:1fr 1fr; gap:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        @csrf
        <div style="grid-column: 1 / -1;"><label>Name</label><input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:10px;"></div>
        <div>
            <label>Category</label>
            <select name="category_id" style="width:100%; padding:10px;">
                @foreach(\$categories as \$category) <option value="{{ \$category->id }}">{{ \$category->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label>Brand</label>
            <select name="brand_id" style="width:100%; padding:10px;">
                <option value="">None</option>
                @foreach(\$brands as \$brand) <option value="{{ \$brand->id }}">{{ \$brand->name }}</option> @endforeach
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
        <button type="submit" style="grid-column: 1 / -1; background:#18BC9C; color:#fff; border:none; padding:10px 20px; font-size:16px;">Save Product</button>
    </form>
@endsection
HTML
);

echo "Robust Phase 3 fixed.\n";
