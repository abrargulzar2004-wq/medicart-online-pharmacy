<?php
$controllersDir = __DIR__ . '/app/Http/Controllers/Admin';
$viewsDir = __DIR__ . '/resources/views/admin';
if (!is_dir($viewsDir . '/brands')) mkdir($viewsDir . '/brands', 0777, true);
if (!is_dir($viewsDir . '/products')) mkdir($viewsDir . '/products', 0777, true);
if (!is_dir($viewsDir . '/inventory')) mkdir($viewsDir . '/inventory', 0777, true);

// 1. BrandController
$brandController = <<<PHP
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index() {
        \$brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }
    public function create() {
        return view('admin.brands.create');
    }
    public function store(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255'
        ]);
        Brand::create([
            'name' => \$request->name,
            'slug' => Str::slug(\$request->name),
            'status' => \$request->has('status')
        ]);
        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully');
    }
    public function edit(Brand \$brand) {
        return view('admin.brands.edit', compact('brand'));
    }
    public function update(Request \$request, Brand \$brand) {
        \$request->validate(['name' => 'required|string|max:255']);
        \$brand->update([
            'name' => \$request->name,
            'slug' => Str::slug(\$request->name),
            'status' => \$request->has('status')
        ]);
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully');
    }
    public function destroy(Brand \$brand) {
        \$brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully');
    }
}
PHP;
file_put_contents($controllersDir . '/BrandController.php', $brandController);

// 2. ProductController
$productController = <<<PHP
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index() {
        \$products = Product::with(['category', 'brand'])->get();
        return view('admin.products.index', compact('products'));
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
            'stock_quantity' => 'required|integer'
        ]);
        \$data = \$request->all();
        \$data['slug'] = Str::slug(\$request->name) . '-' . time();
        \$data['requires_prescription'] = \$request->has('requires_prescription');
        \$data['status'] = \$request->has('status');
        \$data['is_featured'] = \$request->has('is_featured');
        \$data['is_new_arrival'] = \$request->has('is_new_arrival');
        \$data['is_best_seller'] = \$request->has('is_best_seller');
        
        \$product = Product::create(\$data);
        
        // Handle image upload here (dummy for now)
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

// 3. InventoryController
$inventoryController = <<<PHP
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        \$products = Product::with('category')->orderBy('stock_quantity', 'asc')->get();
        return view('admin.inventory.index', compact('products'));
    }
}
PHP;
file_put_contents($controllersDir . '/InventoryController.php', $inventoryController);

// Views: Layout update
$layout = file_get_contents($viewsDir . '/layout.blade.php');
$layout = str_replace(
    '<li><a href="#">Products</a></li>',
    '<li><a href="{{ route(\'admin.brands.index\') }}">Brands</a></li><li><a href="{{ route(\'admin.products.index\') }}">Products</a></li><li><a href="{{ route(\'admin.inventory.index\') }}">Inventory</a></li>',
    $layout
);
file_put_contents($viewsDir . '/layout.blade.php', $layout);

// Views: Brands
file_put_contents($viewsDir . '/brands/index.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Brands</h1>
        <a href="{{ route('admin.brands.create') }}" style="background:#18BC9C; color:#fff; padding:10px 15px; text-decoration:none; border-radius:4px;">+ Add Brand</a>
    </div>
    <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:20px;">
        <thead>
            <tr style="background:#34495E; color:#fff;">
                <th style="padding:15px; text-align:left;">ID</th>
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\$brands as \$brand)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$brand->id }}</td>
                <td style="padding:15px;">{{ \$brand->name }}</td>
                <td style="padding:15px;">{{ \$brand->status ? 'Active' : 'Inactive' }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.brands.edit', \$brand) }}" style="color:#3498DB;">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
HTML
);

file_put_contents($viewsDir . '/brands/create.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Add Brand</h1>
    <form method="POST" action="{{ route('admin.brands.store') }}" style="background:#fff; padding:30px;">
        @csrf
        <div style="margin-bottom:15px;"><label>Name</label><input type="text" name="name" required style="width:100%; padding:10px;"></div>
        <div style="margin-bottom:20px;"><label><input type="checkbox" name="status" checked> Active</label></div>
        <button type="submit" style="background:#18BC9C; color:#fff; border:none; padding:10px 20px;">Save Brand</button>
    </form>
@endsection
HTML
);

file_put_contents($viewsDir . '/brands/edit.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Edit Brand</h1>
    <form method="POST" action="{{ route('admin.brands.update', \$brand) }}" style="background:#fff; padding:30px;">
        @csrf @method('PUT')
        <div style="margin-bottom:15px;"><label>Name</label><input type="text" name="name" value="{{ \$brand->name }}" required style="width:100%; padding:10px;"></div>
        <div style="margin-bottom:20px;"><label><input type="checkbox" name="status" {{ \$brand->status ? 'checked' : '' }}> Active</label></div>
        <button type="submit" style="background:#18BC9C; color:#fff; border:none; padding:10px 20px;">Update Brand</button>
    </form>
@endsection
HTML
);

// Views: Products
file_put_contents($viewsDir . '/products/index.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" style="background:#18BC9C; color:#fff; padding:10px 15px; text-decoration:none; border-radius:4px;">+ Add Product</a>
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
            @foreach(\$products as \$product)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$product->name }}</td>
                <td style="padding:15px;">{{ \$product->category->name ?? 'N/A' }}</td>
                <td style="padding:15px;">\${{ \$product->price }}</td>
                <td style="padding:15px;">{{ \$product->stock_quantity }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.products.edit', \$product) }}" style="color:#3498DB;">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
HTML
);

file_put_contents($viewsDir . '/products/create.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Add Product</h1>
    <form method="POST" action="{{ route('admin.products.store') }}" style="background:#fff; padding:30px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        @csrf
        <div style="grid-column: 1 / -1;"><label>Name</label><input type="text" name="name" required style="width:100%; padding:10px;"></div>
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
        <div style="grid-column: 1 / -1;"><label>Description</label><textarea name="description" style="width:100%; padding:10px;" rows="4"></textarea></div>
        <div><label>Price</label><input type="number" step="0.01" name="price" required style="width:100%; padding:10px;"></div>
        <div><label>Stock Quantity</label><input type="number" name="stock_quantity" required style="width:100%; padding:10px;"></div>
        <div><label>SKU</label><input type="text" name="sku" required style="width:100%; padding:10px;"></div>
        <div><label>Batch Number</label><input type="text" name="batch_number" style="width:100%; padding:10px;"></div>
        <div style="grid-column: 1 / -1; display:flex; gap:20px;">
            <label><input type="checkbox" name="requires_prescription"> Requires Prescription</label>
            <label><input type="checkbox" name="is_featured"> Featured</label>
            <label><input type="checkbox" name="is_new_arrival"> New Arrival</label>
            <label><input type="checkbox" name="is_best_seller"> Best Seller</label>
            <label><input type="checkbox" name="status" checked> Active</label>
        </div>
        <button type="submit" style="grid-column: 1 / -1; background:#18BC9C; color:#fff; border:none; padding:10px 20px;">Save Product</button>
    </form>
@endsection
HTML
);

file_put_contents($viewsDir . '/products/edit.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Edit Product</h1>
    <form method="POST" action="{{ route('admin.products.update', \$product) }}" style="background:#fff; padding:30px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        @csrf @method('PUT')
        <div style="grid-column: 1 / -1;"><label>Name</label><input type="text" name="name" value="{{ \$product->name }}" required style="width:100%; padding:10px;"></div>
        <div><label>Price</label><input type="number" step="0.01" name="price" value="{{ \$product->price }}" required style="width:100%; padding:10px;"></div>
        <div><label>Stock Quantity</label><input type="number" name="stock_quantity" value="{{ \$product->stock_quantity }}" required style="width:100%; padding:10px;"></div>
        <div style="grid-column: 1 / -1; display:flex; gap:20px;">
            <label><input type="checkbox" name="requires_prescription" {{ \$product->requires_prescription ? 'checked' : '' }}> Prescription</label>
            <label><input type="checkbox" name="status" {{ \$product->status ? 'checked' : '' }}> Active</label>
        </div>
        <button type="submit" style="grid-column: 1 / -1; background:#18BC9C; color:#fff; border:none; padding:10px 20px;">Update Product</button>
    </form>
@endsection
HTML
);

// Views: Inventory
file_put_contents($viewsDir . '/inventory/index.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Inventory Management</h1>
    <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:20px;">
        <thead>
            <tr style="background:#34495E; color:#fff;">
                <th style="padding:15px; text-align:left;">Product Name</th>
                <th style="padding:15px; text-align:left;">SKU</th>
                <th style="padding:15px; text-align:left;">Stock Status</th>
                <th style="padding:15px; text-align:left;">Stock Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\$products as \$product)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$product->name }}</td>
                <td style="padding:15px;">{{ \$product->sku }}</td>
                <td style="padding:15px;">
                    @if(\$product->stock_quantity == 0)
                        <span style="color:#E74C3C; font-weight:bold;">Out of Stock</span>
                    @elseif(\$product->stock_quantity <= 10)
                        <span style="color:#F39C12; font-weight:bold;">Low Stock</span>
                    @else
                        <span style="color:#2ECC71; font-weight:bold;">In Stock</span>
                    @endif
                </td>
                <td style="padding:15px;">{{ \$product->stock_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
HTML
);

echo "Phase 3 Scaffold generated.\n";
