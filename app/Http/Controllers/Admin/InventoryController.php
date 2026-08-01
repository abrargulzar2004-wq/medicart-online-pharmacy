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

    public function update(Request $request, Product $product) {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $product) {
            $product->update([
                'stock_quantity' => $request->stock_quantity
            ]);
        });

        return back()->with('success', 'Stock updated successfully.');
    }
}