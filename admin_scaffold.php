<?php

$controllersDir = __DIR__ . '/app/Http/Controllers/Admin';
$viewsDir = __DIR__ . '/resources/views/admin';
if (!is_dir($controllersDir)) mkdir($controllersDir, 0777, true);
if (!is_dir($viewsDir)) mkdir($viewsDir, 0777, true);
if (!is_dir($viewsDir . '/categories')) mkdir($viewsDir . '/categories', 0777, true);

// AdminController
$adminController = <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'pendingOrders', 'completedOrders', 'lowStockProducts', 'outOfStockProducts'
        ));
    }
}
PHP;
file_put_contents($controllersDir . '/AdminController.php', $adminController);

// CategoryController
$categoryController = <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        \$categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => \$request->name,
            'slug' => Str::slug(\$request->name),
            'description' => \$request->description,
            'status' => \$request->has('status')
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }
}
PHP;
file_put_contents($controllersDir . '/CategoryController.php', $categoryController);

// Views: Layout
$layout = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - MediCart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #2C3E50; --secondary: #18BC9C; --bg: #ECF0F1; --text: #333; --sidebar-bg: #34495E; --sidebar-hover: #2C3E50; --white: #FFF; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg); color: var(--text); display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 250px; background-color: var(--sidebar-bg); color: var(--white); display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 24px; font-weight: bold; text-align: center; background-color: var(--primary); letter-spacing: 1px; border-bottom: 1px solid #1A252F; }
        .nav-links { list-style: none; padding: 0; margin: 0; overflow-y: auto; flex-grow: 1; }
        .nav-links li { border-bottom: 1px solid #2C3E50; }
        .nav-links a { display: block; padding: 15px 20px; color: var(--white); text-decoration: none; transition: background 0.3s; font-size: 16px; }
        .nav-links a:hover { background-color: var(--sidebar-hover); border-left: 4px solid var(--secondary); padding-left: 16px; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background-color: var(--white); padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .user-info { font-weight: bold; display: flex; align-items: center; gap: 15px; }
        .btn-logout { background-color: #E74C3C; color: #FFF; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-logout:hover { background-color: #C0392B; }
        .content { padding: 30px; }
        h1 { margin-top: 0; font-size: 28px; color: var(--primary); }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">MediCart Admin</div>
        <ul class="nav-links">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="#">Orders</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="topbar">
            <div>Welcome to Admin Panel</div>
            <div class="user-info">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
HTML;
file_put_contents($viewsDir . '/layout.blade.php', $layout);

// Views: Dashboard
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
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
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
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: [1200, 1900, 3000, 5000, 2000, 3000],
                backgroundColor: '#3498DB'
            }]
        }
    });
</script>
@endpush
HTML;
file_put_contents($viewsDir . '/dashboard.blade.php', $dashboard);

// Views: Category Index
$catIndex = <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Categories</h1>
        <a href="{{ route('admin.categories.create') }}" style="background:#18BC9C; color:#fff; padding:10px 15px; text-decoration:none; border-radius:4px;">+ Add Category</a>
    </div>
    
    <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background:#34495E; color:#fff;">
                <th style="padding:15px; text-align:left;">ID</th>
                <th style="padding:15px; text-align:left;">Name</th>
                <th style="padding:15px; text-align:left;">Slug</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\$categories as \$category)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$category->id }}</td>
                <td style="padding:15px;">{{ \$category->name }}</td>
                <td style="padding:15px;">{{ \$category->slug }}</td>
                <td style="padding:15px;">{{ \$category->status ? 'Active' : 'Inactive' }}</td>
                <td style="padding:15px; text-align:center;">
                    <a href="#" style="color:#3498DB; text-decoration:none; margin-right:10px;">Edit</a>
                    <a href="#" style="color:#E74C3C; text-decoration:none;">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
HTML;
file_put_contents($viewsDir . '/categories/index.blade.php', $catIndex);

// Views: Category Create
$catCreate = <<<HTML
@extends('admin.layout')
@section('content')
    <h1>Add Category</h1>
    
    <form method="POST" action="{{ route('admin.categories.store') }}" style="background:#fff; padding:30px; border-radius:8px; max-width:600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        @csrf
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Category Name</label>
            <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;"></textarea>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:flex; align-items:center; font-weight:bold;">
                <input type="checkbox" name="status" checked style="margin-right:10px;"> Active
            </label>
        </div>
        <button type="submit" style="background:#18BC9C; color:#fff; border:none; padding:10px 20px; font-size:16px; border-radius:4px; cursor:pointer;">Save Category</button>
    </form>
@endsection
HTML;
file_put_contents($viewsDir . '/categories/create.blade.php', $catCreate);

echo "Admin scaffolding generated.\n";
