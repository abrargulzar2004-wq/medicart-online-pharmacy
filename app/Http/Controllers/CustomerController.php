<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard() {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('customer.dashboard', compact('orders'));
    }

    public function reuploadPrescription(Request $request, Order $order) {
        if ($order->user_id !== Auth::id() || $order->prescription_status !== 'Rejected') {
            abort(403);
        }

        $request->validate([
            'prescription' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $path = $request->file('prescription')->store('prescriptions', 'public');
        
        $order->update([
            'prescription_path' => $path,
            'prescription_status' => 'Pending',
            'prescription_remarks' => null
        ]);

        return back()->with('success', 'Prescription re-uploaded successfully. Awaiting admin approval.');
    }
    public function invoice(Order $order) {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load('items.product');
        return view('customer.invoice', compact('order'));
    }
}