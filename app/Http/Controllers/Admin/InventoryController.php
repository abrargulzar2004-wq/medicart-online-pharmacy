<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $products = Product::with('category')->orderBy('stock_quantity', 'asc')->get();
        return view('admin.inventory.index', compact('products'));
    }
}