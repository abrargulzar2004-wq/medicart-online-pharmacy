<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index() {
        $cartItems = [];
        $total = 0;
        $requiresPrescription = false;

        // DB Cart (User is always authenticated here due to middleware)
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            foreach ($cart->items as $item) {
                $product = $item->product;
                if (!$product) continue;
                
                $subtotal = $product->price * $item->quantity;
                $total += $subtotal;
                if ($product->requires_prescription) $requiresPrescription = true;
                
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                    'image' => $product->imageUrl()
                ];
            }
        }

        return view('cart.index', compact('cartItems', 'total', 'requiresPrescription'));
    }

    public function add(Request $request, $id) {
        $product = Product::visibleInStorefront()->where('id', $id)->firstOrFail();
        $quantity = $request->quantity ?? 1;

        if ($product->stock_quantity < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // Add to DB Cart (User is always authenticated here)
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            if ($cartItem->quantity + $quantity > $product->stock_quantity) {
                return back()->with('error', 'Cannot add more than available stock.');
            }
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Product added to cart successfully!');
    }
}