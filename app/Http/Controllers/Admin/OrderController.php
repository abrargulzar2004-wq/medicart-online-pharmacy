<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        $query = Order::with('user')->orderBy('created_at', 'desc');
        
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        
        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order) {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order) {
        $request->validate([
            'order_status' => 'required|string',
            'payment_status' => 'required|string'
        ]);
        
        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status
        ]);
        
        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePrescription(Request $request, Order $order) {
        $request->validate([
            'prescription_status' => 'required|in:Approved,Rejected',
            'prescription_remarks' => 'nullable|string'
        ]);
        
        $order->update([
            'prescription_status' => $request->prescription_status,
            'prescription_remarks' => $request->prescription_remarks
        ]);
        
        return back()->with('success', 'Prescription status updated.');
    }
}