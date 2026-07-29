@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h1>My Dashboard</h1>
        <a href="{{ route('wishlist.index') }}" class="btn btn-primary"><i class="ph ph-heart"></i> My Wishlist</a>
    </div>
    
    <div class="card" style="padding: 30px;">
        <h2>Order History</h2>
        @if($orders->isEmpty())
            <p>You haven't placed any orders yet.</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--navy); color: #fff;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Order #</th>
                        <th style="padding: 15px; text-align: left;">Date</th>
                        <th style="padding: 15px; text-align: left;">Total</th>
                        <th style="padding: 15px; text-align: left;">Status</th>
                        <th style="padding: 15px; text-align: left;">Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid #E2E8F0;">
                            <td style="padding: 15px; font-weight: 600;">
                                {{ $order->order_number }}
                                <br><a href="{{ route('customer.order.invoice', $order) }}" style="font-size:12px; color:var(--primary);">View Invoice</a>
                            </td>
                            <td style="padding: 15px;">{{ $order->created_at->format('d M, Y') }}</td>
                            <td style="padding: 15px;">${{ $order->final_amount }}</td>
                            <td style="padding: 15px;">
                                <span style="background: #F1F5F9; padding: 4px 8px; border-radius: 4px; font-size: 12px; border:1px solid #E2E8F0;">{{ $order->order_status }}</span>
                            </td>
                            <td style="padding: 15px;">
                                @if($order->prescription_status === 'Pending')
                                    <span style="color: #D97706; font-weight: 600;">Under Review</span>
                                @elseif($order->prescription_status === 'Approved')
                                    <span style="color: #059669; font-weight: 600;">Approved</span>
                                @elseif($order->prescription_status === 'Rejected')
                                    <span style="color: #DC2626; font-weight: 600;">Rejected</span>
                                    <p style="font-size: 12px; color: #DC2626; margin: 5px 0;">Reason: {{ $order->prescription_remarks }}</p>
                                    <form method="POST" action="{{ route('customer.prescription.reupload', $order) }}" enctype="multipart/form-data" style="margin-top: 10px;">
                                        @csrf
                                        <input type="file" name="prescription" required style="font-size: 12px; margin-bottom: 5px; width:100%;">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Re-upload</button>
                                    </form>
                                @else
                                    <span style="color: var(--text-muted);">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
