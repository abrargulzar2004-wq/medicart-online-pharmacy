<?php
$controllersDir = __DIR__ . '/app/Http/Controllers';
$viewsDir = __DIR__ . '/resources/views';
if (!is_dir($viewsDir . '/cart')) mkdir($viewsDir . '/cart', 0777, true);
if (!is_dir($viewsDir . '/checkout')) mkdir($viewsDir . '/checkout', 0777, true);
if (!is_dir($viewsDir . '/customer')) mkdir($viewsDir . '/customer', 0777, true);

// 1. CartController
$cartController = <<<PHP
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
        \$cartItems = [];
        \$total = 0;
        \$requiresPrescription = false;

        if (Auth::check()) {
            // DB Cart
            \$cart = Cart::where('user_id', Auth::id())->first();
            if (\$cart) {
                foreach (\$cart->items as \$item) {
                    \$product = \$item->product;
                    \$subtotal = \$product->price * \$item->quantity;
                    \$total += \$subtotal;
                    if (\$product->requires_prescription) \$requiresPrescription = true;
                    
                    \$cartItems[] = [
                        'id' => \$product->id,
                        'name' => \$product->name,
                        'price' => \$product->price,
                        'quantity' => \$item->quantity,
                        'subtotal' => \$subtotal,
                        'image' => \$product->images->where('is_primary', 1)->first() ? asset('storage/' . \$product->images->where('is_primary', 1)->first()->image_path) : 'https://via.placeholder.com/50'
                    ];
                }
            }
        } else {
            // Session Cart
            \$sessionCart = session()->get('cart', []);
            foreach (\$sessionCart as \$id => \$details) {
                \$product = Product::find(\$id);
                if(\$product) {
                    \$subtotal = \$product->price * \$details['quantity'];
                    \$total += \$subtotal;
                    if (\$product->requires_prescription) \$requiresPrescription = true;
                    
                    \$cartItems[] = [
                        'id' => \$product->id,
                        'name' => \$product->name,
                        'price' => \$product->price,
                        'quantity' => \$details['quantity'],
                        'subtotal' => \$subtotal,
                        'image' => \$product->images->where('is_primary', 1)->first() ? asset('storage/' . \$product->images->where('is_primary', 1)->first()->image_path) : 'https://via.placeholder.com/50'
                    ];
                }
            }
        }

        return view('cart.index', compact('cartItems', 'total', 'requiresPrescription'));
    }

    public function add(Request \$request, \$id) {
        \$product = Product::findOrFail(\$id);
        \$quantity = \$request->quantity ?? 1;

        if (\$product->stock_quantity < \$quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        if (Auth::check()) {
            // Add to DB Cart
            \$cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            \$cartItem = CartItem::where('cart_id', \$cart->id)->where('product_id', \$product->id)->first();
            
            if (\$cartItem) {
                if (\$cartItem->quantity + \$quantity > \$product->stock_quantity) {
                    return back()->with('error', 'Cannot add more than available stock.');
                }
                \$cartItem->update(['quantity' => \$cartItem->quantity + \$quantity]);
            } else {
                CartItem::create([
                    'cart_id' => \$cart->id,
                    'product_id' => \$product->id,
                    'quantity' => \$quantity,
                    'price' => \$product->price
                ]);
            }
        } else {
            // Add to Session Cart
            \$cart = session()->get('cart', []);
            if (isset(\$cart[\$id])) {
                if (\$cart[\$id]['quantity'] + \$quantity > \$product->stock_quantity) {
                    return back()->with('error', 'Cannot add more than available stock.');
                }
                \$cart[\$id]['quantity'] += \$quantity;
            } else {
                \$cart[\$id] = ['quantity' => \$quantity];
            }
            session()->put('cart', \$cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    // Called on login by AuthController
    public static function mergeSessionCart(\$userId) {
        \$sessionCart = session()->get('cart', []);
        if (empty(\$sessionCart)) return;

        \$cart = Cart::firstOrCreate(['user_id' => \$userId]);

        foreach (\$sessionCart as \$id => \$details) {
            \$cartItem = CartItem::where('cart_id', \$cart->id)->where('product_id', \$id)->first();
            \$product = Product::find(\$id);
            if (!\$product) continue;

            if (\$cartItem) {
                \$newQty = min(\$cartItem->quantity + \$details['quantity'], \$product->stock_quantity);
                \$cartItem->update(['quantity' => \$newQty]);
            } else {
                CartItem::create([
                    'cart_id' => \$cart->id,
                    'product_id' => \$id,
                    'quantity' => min(\$details['quantity'], \$product->stock_quantity),
                    'price' => \$product->price
                ]);
            }
        }
        session()->forget('cart');
    }
}
PHP;
file_put_contents($controllersDir . '/CartController.php', $cartController);

// 2. CheckoutController
$checkoutController = <<<PHP
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
        \$cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!\$cart || \$cart->items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        
        \$total = 0;
        \$requiresPrescription = false;
        foreach (\$cart->items as \$item) {
            \$total += \$item->price * \$item->quantity;
            if (\$item->product->requires_prescription) \$requiresPrescription = true;
        }

        return view('checkout.index', compact('cart', 'total', 'requiresPrescription'));
    }

    public function process(Request \$request) {
        \$cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!\$cart || \$cart->items->isEmpty()) return redirect()->route('cart.index');

        \$requiresPrescription = false;
        \$totalAmount = 0;
        foreach (\$cart->items as \$item) {
            \$totalAmount += \$item->price * \$item->quantity;
            if (\$item->product->requires_prescription) \$requiresPrescription = true;
        }

        \$rules = [
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:COD,Bank Transfer',
        ];

        if (\$requiresPrescription) {
            \$rules['prescription'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        \$request->validate(\$rules);

        \$prescriptionPath = null;
        if (\$request->hasFile('prescription')) {
            \$prescriptionPath = \$request->file('prescription')->store('prescriptions', 'public');
        }

        // Create Order
        \$order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => \$totalAmount,
            'discount_amount' => 0,
            'final_amount' => \$totalAmount,
            'payment_method' => \$request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'Pending',
            'shipping_address' => \$request->shipping_address,
            'prescription_path' => \$prescriptionPath,
            'prescription_status' => \$requiresPrescription ? 'Pending' : null,
        ]);

        // Create Order Items and Reduce Stock
        foreach (\$cart->items as \$item) {
            OrderItem::create([
                'order_id' => \$order->id,
                'product_id' => \$item->product_id,
                'quantity' => \$item->quantity,
                'price' => \$item->price
            ]);
            
            // Reduce Stock
            \$item->product->decrement('stock_quantity', \$item->quantity);
        }

        // Clear Cart
        \$cart->items()->delete();

        return view('checkout.success', compact('order'));
    }
}
PHP;
file_put_contents($controllersDir . '/CheckoutController.php', $checkoutController);

// 3. CustomerController
$customerController = <<<PHP
<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard() {
        \$orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('customer.dashboard', compact('orders'));
    }

    public function reuploadPrescription(Request \$request, Order \$order) {
        if (\$order->user_id !== Auth::id() || \$order->prescription_status !== 'Rejected') {
            abort(403);
        }

        \$request->validate([
            'prescription' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        \$path = \$request->file('prescription')->store('prescriptions', 'public');
        
        \$order->update([
            'prescription_path' => \$path,
            'prescription_status' => 'Pending',
            'prescription_remarks' => null
        ]);

        return back()->with('success', 'Prescription re-uploaded successfully. Awaiting admin approval.');
    }
}
PHP;
file_put_contents($controllersDir . '/CustomerController.php', $customerController);

// Views: Cart Index
file_put_contents($viewsDir . '/cart/index.blade.php', <<<HTML
@extends('layouts.app')
@section('content')
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px;">Shopping Cart</h1>
    
    @if(empty(\$cartItems))
        <div style="text-align:center; padding: 50px; background:#fff; border-radius:8px;">
            <h2>Your cart is empty.</h2>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div style="display: flex; gap: 30px;">
            <div style="flex-grow: 1;">
                <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <thead style="background: var(--dark); color: #fff;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Product</th>
                            <th style="padding: 15px; text-align: left;">Price</th>
                            <th style="padding: 15px; text-align: left;">Quantity</th>
                            <th style="padding: 15px; text-align: left;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\$cartItems as \$item)
                            <tr style="border-bottom: 1px solid #ECF0F1;">
                                <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                                    <img src="{{ \$item['image'] }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <strong>{{ \$item['name'] }}</strong>
                                </td>
                                <td style="padding: 15px;">\${{ \$item['price'] }}</td>
                                <td style="padding: 15px;">{{ \$item['quantity'] }}</td>
                                <td style="padding: 15px; font-weight: bold;">\${{ \$item['subtotal'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="width: 300px; flex-shrink: 0;">
                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h3 style="margin-top: 0;">Order Summary</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span>Subtotal:</span>
                        <strong>\${{ number_format(\$total, 2) }}</strong>
                    </div>
                    @if(\$requiresPrescription)
                        <div style="background: #fcf8e3; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; border-left: 4px solid #f39c12;">
                            <strong>Note:</strong> One or more items require a prescription. You will be prompted to upload it during checkout.
                        </div>
                    @endif
                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; margin-bottom: 20px;">
                        <span>Total:</span>
                        <span style="color: var(--primary);">\${{ number_format(\$total, 2) }}</span>
                    </div>
                    
                    @auth
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="display: block; text-align: center; width: 100%; box-sizing: border-box;">Proceed to Checkout</a>
                    @else
                        <a href="{{ route('auth.login') }}" class="btn" style="display: block; text-align: center; width: 100%; box-sizing: border-box; background: var(--dark); color: #fff;">Login to Checkout</a>
                    @endauth
                </div>
            </div>
        </div>
    @endif
@endsection
HTML
);

// Views: Checkout Index
file_put_contents($viewsDir . '/checkout/index.blade.php', <<<HTML
@extends('layouts.app')
@section('content')
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px;">Checkout</h1>
    
    <div style="display: flex; gap: 30px;">
        <div style="flex-grow: 1;">
            <form method="POST" action="{{ route('checkout.process') }}" enctype="multipart/form-data" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                @csrf
                <h3>Shipping Details</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Full Shipping Address</label>
                    <textarea name="shipping_address" required rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                </div>
                
                <h3>Payment Method</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom: 10px;"><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery</label>
                    <label style="display:block;"><input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer</label>
                </div>

                @if(\$requiresPrescription)
                    <div style="background: #fdf2e9; border-left: 4px solid #e67e22; padding: 20px; margin-bottom: 20px;">
                        <h3 style="margin-top: 0; color: #d35400;">Prescription Required</h3>
                        <p style="font-size: 14px;">Your cart contains restricted medicines. Please upload a valid doctor's prescription (PDF, JPG, PNG). Your order will be manually reviewed.</p>
                        <input type="file" name="prescription" required accept=".pdf,image/*" style="width: 100%; padding: 10px; background: #fff; border: 1px solid #ccc;">
                    </div>
                @endif
                
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 18px; padding: 15px;">Place Order</button>
            </form>
        </div>
        
        <div style="width: 350px; flex-shrink: 0;">
            <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">Order Summary</h3>
                <ul style="list-style: none; padding: 0; margin: 0 0 20px;">
                    @foreach(\$cart->items as \$item)
                        <li style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span>{{ \$item->quantity }}x {{ \$item->product->name }}</span>
                            <strong>\${{ \$item->price * \$item->quantity }}</strong>
                        </li>
                    @endforeach
                </ul>
                <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold;">
                    <span>Total to Pay:</span>
                    <span style="color: var(--primary);">\${{ number_format(\$total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
HTML
);

// Views: Checkout Success
file_put_contents($viewsDir . '/checkout/success.blade.php', <<<HTML
@extends('layouts.app')
@section('content')
    <div style="text-align: center; padding: 50px; background: #fff; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h1 style="color: var(--primary); font-size: 48px; margin: 0 0 20px;">✅</h1>
        <h2>Order Placed Successfully!</h2>
        <p style="font-size: 18px; margin-bottom: 10px;">Thank you for your purchase. Your order number is <strong>{{ \$order->order_number }}</strong>.</p>
        
        @if(\$order->prescription_status === 'Pending')
            <div style="background: #fdf2e9; border: 1px solid #e67e22; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <strong>Note:</strong> Your order contains prescription medicines and is currently under review by our pharmacists.
            </div>
        @endif
        
        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary" style="margin-top: 20px; display: inline-block;">View Order History</a>
    </div>
@endsection
HTML
);

// Views: Customer Dashboard
file_put_contents($viewsDir . '/customer/dashboard.blade.php', <<<HTML
@extends('layouts.app')
@section('content')
    <h1>My Dashboard</h1>
    
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2>Order History</h2>
        @if(\$orders->isEmpty())
            <p>You haven't placed any orders yet.</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--dark); color: #fff;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Order #</th>
                        <th style="padding: 15px; text-align: left;">Date</th>
                        <th style="padding: 15px; text-align: left;">Total</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                        <th style="padding: 15px; text-align: left;">Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\$orders as \$order)
                        <tr style="border-bottom: 1px solid #ECF0F1;">
                            <td style="padding: 15px; font-weight: bold;">{{ \$order->order_number }}</td>
                            <td style="padding: 15px;">{{ \$order->created_at->format('d M, Y') }}</td>
                            <td style="padding: 15px;">\${{ \$order->final_amount }}</td>
                            <td style="padding: 15px;">
                                <span style="background: #ECF0F1; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ \$order->order_status }}</span>
                            </td>
                            <td style="padding: 15px;">
                                @if(\$order->prescription_status === 'Pending')
                                    <span style="color: #f39c12; font-weight: bold;">Under Review</span>
                                @elseif(\$order->prescription_status === 'Approved')
                                    <span style="color: #2ECC71; font-weight: bold;">Approved</span>
                                @elseif(\$order->prescription_status === 'Rejected')
                                    <span style="color: #E74C3C; font-weight: bold;">Rejected</span>
                                    <p style="font-size: 12px; color: #E74C3C; margin: 5px 0;">Reason: {{ \$order->prescription_remarks }}</p>
                                    <form method="POST" action="{{ route('customer.prescription.reupload', \$order) }}" enctype="multipart/form-data" style="margin-top: 10px;">
                                        @csrf
                                        <input type="file" name="prescription" required style="font-size: 12px; margin-bottom: 5px; width:100%;">
                                        <button type="submit" style="background: #3498DB; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Re-upload</button>
                                    </form>
                                @else
                                    <span style="color: #7f8c8d;">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
HTML
);

echo "Phase 4 Part 2 complete.\n";
