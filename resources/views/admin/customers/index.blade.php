@extends('admin.layout')

@section('content')
<div class="flex justify-between items-center" style="margin-bottom: 20px;">
    <h1>Customers</h1>
</div>

<div class="admin-card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background: #0F172A; color: #fff;">
            <tr>
                <th style="padding: 12px; text-align: left;">ID</th>
                <th style="padding: 12px; text-align: left;">Name</th>
                <th style="padding: 12px; text-align: left;">Email</th>
                <th style="padding: 12px; text-align: left;">Joined Date</th>
                <th style="padding: 12px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #E2E8F0;">
                    <td style="padding: 12px;">{{ $customer->id }}</td>
                    <td style="padding: 12px; font-weight: 600;">{{ $customer->name }}</td>
                    <td style="padding: 12px;">{{ $customer->email }}</td>
                    <td style="padding: 12px;">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="admin-link" style="margin-right:10px;">Edit</a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color:#DC2626; text-decoration:none; font-weight:600; background:none; border:none; cursor:pointer; padding:0;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #64748B;">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
