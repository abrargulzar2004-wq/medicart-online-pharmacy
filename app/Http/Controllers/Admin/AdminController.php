<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
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

        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->count();

        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        // PostgreSQL-compatible monthly sales
        $salesData = Order::select(
                DB::raw('SUM(final_amount) as total'),
                DB::raw('EXTRACT(MONTH FROM created_at) as month')
            )
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get();

        $chartLabels = [];
        $chartValues = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Initialize all months with 0
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = $months[$i - 1];
            $chartValues[$i] = 0;
        }

        foreach ($salesData as $data) {
            $month = (int) $data->month;
            $chartValues[$month] = $data->total;
        }

        $chartValues = array_values($chartValues);

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'pendingOrders',
            'completedOrders',
            'lowStockProducts',
            'outOfStockProducts',
            'chartLabels',
            'chartValues'
        ));
    }
}