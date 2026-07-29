<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index() {
        $wishlists = Wishlist::with(['product.category', 'product.brand', 'product.images'])
            ->where('user_id', Auth::id())
            ->get()
            ->filter(fn ($item) => $item->product && $item->product->hasValidImage());

        return view('customer.wishlist', compact('wishlists'));
    }

    public function add($id) {
        $product = Product::visibleInStorefront()->where('id', $id)->firstOrFail();
        
        $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
        
        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
            return back()->with('success', 'Product added to your wishlist!');
        }
        
        return back()->with('info', 'Product is already in your wishlist.');
    }

    public function remove($id) {
        Wishlist::where('user_id', Auth::id())->where('product_id', $id)->delete();
        return back()->with('success', 'Product removed from wishlist.');
    }
}
