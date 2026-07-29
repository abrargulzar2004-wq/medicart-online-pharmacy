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
        $totalRevenue = Order::where('payment_status', 'paid')->sum('final_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        
        $pendingOrders = Order::where('order_status', 'Pending')->count();
        $completedOrders = Order::where('order_status', 'Delivered')->count();
        
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        // Chart Data: Group Revenue by Month for the current year
        $salesData = Order::select(
                DB::raw('sum(final_amount) as total'), 
                DB::raw('MONTH(created_at) as month')
            )
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $chartLabels = [];
        $chartValues = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Initialize with 0s
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = $months[$i - 1];
            $chartValues[$i] = 0;
        }
        
        foreach ($salesData as $data) {
            $chartValues[$data->month] = $data->total;
        }
        
        $chartValues = array_values($chartValues);

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'pendingOrders', 'completedOrders', 'lowStockProducts', 'outOfStockProducts',
            'chartLabels', 'chartValues'
        ));
    }
}