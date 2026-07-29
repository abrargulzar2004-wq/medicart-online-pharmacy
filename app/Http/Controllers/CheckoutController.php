<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index() {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        
        $total = 0;
        $requiresPrescription = false;
        foreach ($cart->items as $item) {
            $total += $item->product->price * $item->quantity;
            if ($item->product->requires_prescription) $requiresPrescription = true;
        }

        return view('checkout.index', compact('cart', 'total', 'requiresPrescription'));
    }

    public function process(Request $request) {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) return redirect()->route('cart.index');

        $requiresPrescription = false;
        $totalAmount = 0;
        foreach ($cart->items as $item) {
            $totalAmount += $item->product->price * $item->quantity;
            if ($item->product->requires_prescription) $requiresPrescription = true;
        }

        $rules = [
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:COD,Bank Transfer',
        ];

        if ($requiresPrescription) {
            $rules['prescription'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $request->validate($rules);

        $prescriptionPath = null;
        if ($request->hasFile('prescription')) {
            $prescriptionPath = $request->file('prescription')->store('prescriptions', 'public');
        }

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $totalAmount,
            'discount_amount' => 0,
            'final_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'Pending',
            'shipping_address' => $request->shipping_address,
            'prescription_path' => $prescriptionPath,
            'prescription_status' => $requiresPrescription ? 'Pending' : null,
        ]);

        // Create Order Items and Reduce Stock
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
            
            // Reduce Stock
            $item->product->decrement('stock_quantity', $item->quantity);
        }

        // Clear Cart
        $cart->items()->delete();

        return view('checkout.success', compact('order'));
    }
}