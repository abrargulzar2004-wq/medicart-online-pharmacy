<?php
$controllersDir = __DIR__ . '/app/Http/Controllers/Admin';
$viewsDir = __DIR__ . '/resources/views/admin';
if (!is_dir($viewsDir . '/orders')) mkdir($viewsDir . '/orders', 0777, true);

// 1. Admin OrderController
$orderController = <<<PHP
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request \$request) {
        \$query = Order::with('user')->orderBy('created_at', 'desc');
        
        if (\$request->filled('status')) {
            \$query->where('order_status', \$request->status);
        }
        
        \$orders = \$query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order \$order) {
        \$order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request \$request, Order \$order) {
        \$request->validate([
            'order_status' => 'required|string',
            'payment_status' => 'required|string'
        ]);
        
        \$order->update([
            'order_status' => \$request->order_status,
            'payment_status' => \$request->payment_status
        ]);
        
        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePrescription(Request \$request, Order \$order) {
        \$request->validate([
            'prescription_status' => 'required|in:Approved,Rejected',
            'prescription_remarks' => 'nullable|string'
        ]);
        
        \$order->update([
            'prescription_status' => \$request->prescription_status,
            'prescription_remarks' => \$request->prescription_remarks
        ]);
        
        return back()->with('success', 'Prescription status updated.');
    }
}
PHP;
file_put_contents($controllersDir . '/OrderController.php', $orderController);

// Update Layout Sidebar
$layout = file_get_contents($viewsDir . '/layout.blade.php');
$layout = str_replace(
    '<li><a href="#">Orders</a></li>',
    '<li><a href="{{ route(\'admin.orders.index\') }}">Orders</a></li>',
    $layout
);
file_put_contents($viewsDir . '/layout.blade.php', $layout);

// Views: Admin Orders Index
file_put_contents($viewsDir . '/orders/index.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Order Management</h1>
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex; gap:10px;">
            <select name="status" style="padding:8px; border:1px solid #ccc; border-radius:4px;">
                <option value="">All Orders</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" style="background:#3498DB; color:#fff; border:none; padding:8px 15px; border-radius:4px; cursor:pointer;">Filter</button>
        </form>
    </div>
    
    <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background:#34495E; color:#fff;">
                <th style="padding:15px; text-align:left;">Order ID</th>
                <th style="padding:15px; text-align:left;">Customer</th>
                <th style="padding:15px; text-align:left;">Total</th>
                <th style="padding:15px; text-align:left;">Status</th>
                <th style="padding:15px; text-align:left;">Prescription</th>
                <th style="padding:15px; text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\$orders as \$order)
            <tr style="border-bottom:1px solid #ECF0F1;">
                <td style="padding:15px;">{{ \$order->order_number }}</td>
                <td style="padding:15px;">{{ \$order->user->name ?? 'Guest' }}</td>
                <td style="padding:15px;">\${{ \$order->final_amount }}</td>
                <td style="padding:15px;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight:bold; background: {{ \$order->order_status == 'Pending' ? '#F39C12' : (\$order->order_status == 'Delivered' ? '#2ECC71' : '#3498DB') }}; color:#fff;">
                        {{ \$order->order_status }}
                    </span>
                </td>
                <td style="padding:15px;">
                    @if(\$order->prescription_status)
                        <span style="color: {{ \$order->prescription_status == 'Approved' ? '#2ECC71' : (\$order->prescription_status == 'Rejected' ? '#E74C3C' : '#F39C12') }}; font-weight:bold;">{{ \$order->prescription_status }}</span>
                    @else
                        <span style="color:#7f8c8d;">None</span>
                    @endif
                </td>
                <td style="padding:15px; text-align:center;">
                    <a href="{{ route('admin.orders.show', \$order) }}" style="background:#2C3E50; color:#fff; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:14px;">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">{{ \$orders->links() }}</div>
@endsection
HTML
);

// Views: Admin Orders Show
file_put_contents($viewsDir . '/orders/show.blade.php', <<<HTML
@extends('admin.layout')
@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Order Details: {{ \$order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" style="color:#3498DB; text-decoration:none;">&larr; Back to Orders</a>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:20px;">
        <!-- Left Column: Details -->
        <div>
            <div style="background:#fff; padding:20px; border-radius:8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom:30px;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Customer Information</h3>
                <p><strong>Name:</strong> {{ \$order->user->name ?? 'Guest' }}</p>
                <p><strong>Email:</strong> {{ \$order->user->email ?? 'N/A' }}</p>
                <p><strong>Shipping Address:</strong><br>{{ nl2br(\$order->shipping_address) }}</p>
            </div>

            <div style="background:#fff; padding:20px; border-radius:8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Order Items</h3>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="text-align:left; border-bottom:2px solid #ECF0F1;">
                            <th style="padding:10px 0;">Product</th>
                            <th style="padding:10px 0;">Qty</th>
                            <th style="padding:10px 0;">Price</th>
                            <th style="padding:10px 0;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\$order->items as \$item)
                        <tr style="border-bottom:1px solid #ECF0F1;">
                            <td style="padding:10px 0;">{{ \$item->product->name ?? 'Deleted Product' }}
                                @if(\$item->product && \$item->product->requires_prescription) <span style="color:#E74C3C; font-weight:bold; font-size:12px;">(Rx)</span> @endif
                            </td>
                            <td style="padding:10px 0;">{{ \$item->quantity }}</td>
                            <td style="padding:10px 0;">\${{ \$item->price }}</td>
                            <td style="padding:10px 0;">\${{ \$item->price * \$item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="text-align:right; margin-top:20px; font-size:20px;">
                    <strong>Total: <span style="color:#2ECC71;">\${{ \$order->final_amount }}</span></strong>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions -->
        <div>
            @if(\$order->prescription_status)
            <div style="background:#fff; padding:20px; border-radius:8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom:30px; border-left:5px solid #F39C12;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Prescription Review</h3>
                <p>Status: <strong>{{ \$order->prescription_status }}</strong></p>
                <div style="margin: 15px 0;">
                    <a href="{{ asset('storage/' . \$order->prescription_path) }}" target="_blank" style="display:inline-block; background:#3498DB; color:#fff; padding:10px 20px; border-radius:4px; text-decoration:none;">View Uploaded Prescription</a>
                </div>
                
                <form action="{{ route('admin.orders.prescription.update', \$order) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Action</label>
                        <select name="prescription_status" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="Pending" {{ \$order->prescription_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ \$order->prescription_status == 'Approved' ? 'selected' : '' }}>Approve</option>
                            <option value="Rejected" {{ \$order->prescription_status == 'Rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Remarks (Optional, required if rejecting)</label>
                        <textarea name="prescription_remarks" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">{{ \$order->prescription_remarks }}</textarea>
                    </div>
                    <button type="submit" style="background:#F39C12; color:#fff; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">Update Prescription Status</button>
                </form>
            </div>
            @endif

            <div style="background:#fff; padding:20px; border-radius:8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Update Order Status</h3>
                <form action="{{ route('admin.orders.update', \$order) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Payment Status</label>
                        <select name="payment_status" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="pending" {{ \$order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ \$order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ \$order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Order Status</label>
                        <select name="order_status" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            <option value="Pending" {{ \$order->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ \$order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ \$order->order_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ \$order->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ \$order->order_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" style="background:#2ECC71; color:#fff; border:none; padding:10px 20px; border-radius:4px; cursor:pointer; width:100%;">Update Order</button>
                </form>
            </div>
        </div>
    </div>
@endsection
HTML
);

// 3. Customer Invoice (Simple HTML print view)
$customerInvoice = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ \$order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 40px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.total td:nth-child(4) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center" style="margin-bottom:20px;">
        <button onclick="window.print()" style="padding:10px 20px; background:#3498DB; color:#fff; border:none; font-size:16px; cursor:pointer; border-radius:4px;">Print Invoice</button>
        <a href="{{ route('customer.dashboard') }}" style="margin-left:15px; color:#3498DB;">Back to Dashboard</a>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <h1 style="color:#2ECC71; margin:0;">MediCart</h1>
                            </td>
                            <td>
                                Invoice #: {{ \$order->order_number }}<br>
                                Created: {{ \$order->created_at->format('F d, Y') }}<br>
                                Status: {{ \$order->payment_status == 'paid' ? 'Paid' : 'Pending' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>Billed To:</strong><br>
                                {{ \$order->user->name }}<br>
                                {{ \$order->user->email }}<br>
                                {{ nl2br(\$order->shipping_address) }}
                            </td>
                            <td>
                                <strong>MediCart Pharmacy</strong><br>
                                123 Healthcare Ave<br>
                                Wellness City, 10001
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td class="text-center">Price</td>
                <td class="text-center">Quantity</td>
                <td class="text-right">Total</td>
            </tr>

            @foreach(\$order->items as \$item)
            <tr class="item">
                <td>{{ \$item->product->name }}</td>
                <td class="text-center">\${{ \$item->price }}</td>
                <td class="text-center">{{ \$item->quantity }}</td>
                <td class="text-right">\${{ \$item->price * \$item->quantity }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right" style="padding-top:20px; font-size:24px; color:#2ECC71;">
                   Total: \${{ \$order->final_amount }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
file_put_contents(dirname($viewsDir) . '/customer/invoice.blade.php', $customerInvoice);

// Add invoice route method in CustomerController
$customerControllerPath = $controllersDir . '/../CustomerController.php';
$customerControllerContent = file_get_contents($customerControllerPath);
$invoiceMethod = <<<PHP

    public function invoice(Order \$order) {
        if (\$order->user_id !== Auth::id()) abort(403);
        \$order->load('items.product');
        return view('customer.invoice', compact('order'));
    }
}
PHP;
$customerControllerContent = str_replace("}\n}", "}" . $invoiceMethod, $customerControllerContent);
file_put_contents($customerControllerPath, $customerControllerContent);

echo "Final phase generated.\n";
