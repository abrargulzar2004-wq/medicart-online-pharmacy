<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 40px; color: #334155; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #E2E8F0; font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.heading td { background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-weight: 600; }
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
        <button onclick="window.print()" class="btn btn-primary" style="padding:10px 20px; font-size:16px; border:none; cursor:pointer;">Print Invoice</button>
        <a href="{{ route('customer.dashboard') }}" style="margin-left:15px; color:#2563EB; font-weight:500;">Back to Dashboard</a>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <h1 style="color:#0F172A; margin:0;">MediCart</h1>
                            </td>
                            <td>
                                Invoice #: {{ $order->order_number }}<br>
                                Created: {{ $order->created_at->format('F d, Y') }}<br>
                                Status: {{ $order->payment_status == 'paid' ? 'Paid' : 'Pending' }}
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
                                {{ $order->user->name }}<br>
                                {{ $order->user->email }}<br>
                                {{ nl2br($order->shipping_address) }}
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

            @foreach($order->items as $item)
            <tr class="item">
                <td>{{ $item->product->name }}</td>
                <td class="text-center">${{ $item->price }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">${{ $item->price * $item->quantity }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right" style="padding-top:20px; font-size:24px; color:#2563EB;">
                   Total: ${{ $order->final_amount }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>