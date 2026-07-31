<?php
$controllersDir = __DIR__ . '/app/Http/Controllers';
$viewsDir = __DIR__ . '/resources/views';
if (!is_dir($viewsDir . '/layouts')) mkdir($viewsDir . '/layouts', 0777, true);
if (!is_dir($viewsDir . '/shop')) mkdir($viewsDir . '/shop', 0777, true);

// 1. Layouts App (Storefront)
$layout = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediCart - Online Pharmacy')</title>
    <style>
        :root { --primary: #2ECC71; --secondary: #27AE60; --dark: #2C3E50; --light: #ECF0F1; --text: #333; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .header { background: #fff; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: var(--primary); }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { font-weight: 600; color: var(--dark); transition: color 0.3s; }
        .nav-links a:hover { color: var(--primary); }
        .btn { padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s; border: none; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--secondary); }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; min-height: 70vh; }
        .footer { background: var(--dark); color: #fff; text-align: center; padding: 30px; margin-top: 40px; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .badge { background: #E74C3C; color: #fff; padding: 2px 6px; border-radius: 10px; font-size: 12px; vertical-align: top; }
    </style>
    @stack('styles')
</head>
<body>
    <header class="header">
        <a href="/" class="logo">💊 MediCart</a>
        <nav class="nav-links">
            <a href="/">Home</a>
            <a href="{{ route('shop.index') }}">Shop</a>
            <a href="{{ route('cart.index') }}">Cart <span class="badge">{{ session()->has('cart') ? count(session('cart')) : 0 }}</span></a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                @else
                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                @endif
                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:var(--dark); font-weight:bold; cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('auth.login') }}">Login</a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary">Register</a>
            @endauth
        </nav>
    </header>

    <div class="container">
        @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert-danger">{{ session('error') }}</div> @endif
        
        @yield('content')
    </div>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} MediCart - Online Pharmacy & Healthcare Store. All rights reserved.</p>
    </footer>
    @stack('scripts')
</body>
</html>
HTML;
file_put_contents($viewsDir . '/layouts/app.blade.php', $layout);

// 2. HomeController
$homeController = <<<PHP
<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        \$featuredProducts = Product::where('status', 1)->where('is_featured', 1)->take(4)->get();
        \$newArrivals = Product::where('status', 1)->where('is_new_arrival', 1)->take(4)->get();
        \$categories = Category::where('status', 1)->take(4)->get();
        
        return view('home', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
PHP;
file_put_contents($controllersDir . '/HomeController.php', $homeController);

// 3. Home View
$homeView = <<<HTML
@extends('layouts.app')
@section('content')
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 48px; color: var(--dark); margin-bottom: 10px;">Your Trusted Online Pharmacy</h1>
        <p style="font-size: 18px; color: #666;">Get genuine medicines and healthcare products delivered to your door.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary" style="font-size: 18px; padding: 15px 30px; margin-top: 20px; display: inline-block;">Shop Now</a>
    </div>

    <h2 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px;">Featured Products</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        @foreach(\$featuredProducts as \$product)
            <div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; transition: 0.3s;">
                <img src="{{ \$product->images->where('is_primary', 1)->first() ? asset('storage/' . \$product->images->where('is_primary', 1)->first()->image_path) : 'https://via.placeholder.com/200' }}" alt="{{ \$product->name }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
                <h3 style="margin: 15px 0 10px; font-size: 18px;"><a href="{{ route('shop.show', \$product->slug) }}">{{ \$product->name }}</a></h3>
                <p style="color: var(--primary); font-weight: bold; font-size: 20px; margin: 0 0 15px;">\${{ \$product->price }}</p>
                <form action="{{ route('cart.add', \$product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add to Cart</button>
                </form>
            </div>
        @endforeach
    </div>

    <h2 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px;">Shop by Category</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        @foreach(\$categories as \$category)
            <div style="background: var(--dark); color: #fff; border-radius: 8px; padding: 30px; text-align: center;">
                <h3>{{ \$category->name }}</h3>
                <a href="{{ route('shop.index', ['category' => \$category->id]) }}" class="btn" style="background: #fff; color: var(--dark);">View Products</a>
            </div>
        @endforeach
    </div>
@endsection
HTML;
file_put_contents($viewsDir . '/home.blade.php', $homeView);

// 4. ShopController
$shopController = <<<PHP
<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request \$request) {
        \$query = Product::where('status', 1);

        if (\$request->filled('search')) {
            \$query->where('name', 'like', '%' . \$request->search . '%');
        }
        if (\$request->filled('category')) {
            \$query->where('category_id', \$request->category);
        }
        if (\$request->filled('brand')) {
            \$query->where('brand_id', \$request->brand);
        }

        \$products = \$query->paginate(12);
        \$categories = Category::where('status', 1)->get();
        \$brands = Brand::where('status', 1)->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function show(\$slug) {
        \$product = Product::with(['category', 'brand', 'images'])->where('slug', \$slug)->where('status', 1)->firstOrFail();
        return view('shop.show', compact('product'));
    }
}
PHP;
file_put_contents($controllersDir . '/ShopController.php', $shopController);

// 5. Shop Views
$shopIndex = <<<HTML
@extends('layouts.app')
@section('content')
<div style="display: flex; gap: 30px;">
    <!-- Sidebar Filters -->
    <div style="width: 250px; flex-shrink: 0;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <form method="GET" action="{{ route('shop.index') }}">
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 10px;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing:border-box;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 10px;">Categories</label>
                    <select name="category" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">All Categories</option>
                        @foreach(\$categories as \$category)
                            <option value="{{ \$category->id }}" {{ request('category') == \$category->id ? 'selected' : '' }}>{{ \$category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 10px;">Brands</label>
                    <select name="brand" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">All Brands</option>
                        @foreach(\$brands as \$brand)
                            <option value="{{ \$brand->id }}" {{ request('brand') == \$brand->id ? 'selected' : '' }}>{{ \$brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Apply Filters</button>
                <a href="{{ route('shop.index') }}" class="btn" style="display:block; text-align:center; margin-top:10px; background:#e0e0e0;">Clear</a>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div style="flex-grow: 1;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @forelse(\$products as \$product)
                <div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
                    <img src="{{ \$product->images->where('is_primary', 1)->first() ? asset('storage/' . \$product->images->where('is_primary', 1)->first()->image_path) : 'https://via.placeholder.com/200' }}" alt="{{ \$product->name }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 4px;">
                    <h3 style="margin: 15px 0 5px; font-size: 16px;"><a href="{{ route('shop.show', \$product->slug) }}">{{ \$product->name }}</a></h3>
                    <p style="color: #7f8c8d; font-size: 12px; margin: 0 0 10px;">{{ \$product->category->name ?? 'Uncategorized' }}</p>
                    <p style="color: var(--primary); font-weight: bold; font-size: 18px; margin: 0 0 15px;">\${{ \$product->price }}</p>
                    
                    @if(\$product->stock_quantity > 0)
                        <form action="{{ route('cart.add', \$product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 14px;">Add to Cart</button>
                        </form>
                    @else
                        <button disabled class="btn" style="width: 100%; background: #ccc; cursor: not-allowed; font-size: 14px;">Out of Stock</button>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: #fff; border-radius: 8px;">
                    <h3>No products found.</h3>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 30px;">
            {{ \$products->links() }}
        </div>
    </div>
</div>
@endsection
HTML;
file_put_contents($viewsDir . '/shop/index.blade.php', $shopIndex);

$shopShow = <<<HTML
@extends('layouts.app')
@section('content')
<div style="background: #fff; border-radius: 8px; padding: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; gap: 40px;">
    <!-- Image Gallery -->
    <div style="flex: 1;">
        <img src="{{ \$product->images->where('is_primary', 1)->first() ? asset('storage/' . \$product->images->where('is_primary', 1)->first()->image_path) : 'https://via.placeholder.com/500' }}" alt="{{ \$product->name }}" style="width: 100%; border-radius: 8px; object-fit: cover;">
        <div style="display: flex; gap: 10px; margin-top: 10px;">
            @foreach(\$product->images as \$image)
                <img src="{{ asset('storage/' . \$image->image_path) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; cursor:pointer;">
            @endforeach
        </div>
    </div>
    
    <!-- Product Info -->
    <div style="flex: 1;">
        <h1 style="margin: 0 0 10px; font-size: 32px; color: var(--dark);">{{ \$product->name }}</h1>
        <p style="color: #7f8c8d; font-size: 16px; margin: 0 0 20px;">Category: {{ \$product->category->name ?? 'N/A' }} | Brand: {{ \$product->brand->name ?? 'N/A' }}</p>
        
        <p style="font-size: 32px; font-weight: bold; color: var(--primary); margin: 0 0 20px;">\${{ \$product->price }}</p>
        
        <div style="margin-bottom: 20px;">
            @if(\$product->requires_prescription)
                <span style="background: #f39c12; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 14px; font-weight: bold;">⚠️ Prescription Required</span>
            @endif
        </div>
        
        <p style="color: #555; line-height: 1.6; margin-bottom: 30px;">{{ \$product->description }}</p>
        
        <div style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid var(--primary);">
            <p><strong>Stock Status:</strong> 
                @if(\$product->stock_quantity > 0)
                    <span style="color: var(--primary);">In Stock ({{ \$product->stock_quantity }} available)</span>
                @else
                    <span style="color: #E74C3C;">Out of Stock</span>
                @endif
            </p>
            <p><strong>SKU:</strong> {{ \$product->sku }}</p>
        </div>

        @if(\$product->stock_quantity > 0)
            <form action="{{ route('cart.add', \$product->id) }}" method="POST" style="display: flex; gap: 15px;">
                @csrf
                <input type="number" name="quantity" value="1" min="1" max="{{ \$product->stock_quantity }}" style="padding: 15px; width: 80px; border: 1px solid #ccc; border-radius: 4px; font-size: 18px; text-align: center;">
                <button type="submit" class="btn btn-primary" style="flex-grow: 1; font-size: 18px;">Add to Cart</button>
            </form>
        @else
            <button disabled class="btn" style="width: 100%; padding: 15px; background: #ccc; cursor: not-allowed; font-size: 18px;">Currently Unavailable</button>
        @endif
    </div>
</div>
@endsection
HTML;
file_put_contents($viewsDir . '/shop/show.blade.php', $shopShow);

echo "Phase 4 Part 1 complete (Home & Shop).\n";
