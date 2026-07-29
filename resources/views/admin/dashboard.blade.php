@extends('admin.layout')
@section('content')
    <h1>Dashboard Overview</h1>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="admin-card" style="border-left: 4px solid #2563EB;">
            <h3 style="margin:0 0 10px; color:#64748B; font-size:16px; font-weight:500;">Total Revenue</h3>
            <p style="margin:0; font-size: 28px; font-weight: 700; color: #0F172A;">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="admin-card" style="border-left: 4px solid #059669;">
            <h3 style="margin:0 0 10px; color:#64748B; font-size:16px; font-weight:500;">Total Orders</h3>
            <p style="margin:0; font-size: 28px; font-weight: 700; color: #0F172A;">{{ $totalOrders }}</p>
        </div>
        <div class="admin-card" style="border-left: 4px solid #1E3A8A;">
            <h3 style="margin:0 0 10px; color:#64748B; font-size:16px; font-weight:500;">Total Customers</h3>
            <p style="margin:0; font-size: 28px; font-weight: 700; color: #0F172A;">{{ $totalCustomers }}</p>
        </div>
        <div class="admin-card" style="border-left: 4px solid #64748B;">
            <h3 style="margin:0 0 10px; color:#64748B; font-size:16px; font-weight:500;">Total Products</h3>
            <p style="margin:0; font-size: 28px; font-weight: 700; color: #0F172A;">{{ $totalProducts }}</p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="admin-card">
            <canvas id="salesChart"></canvas>
        </div>
        <div class="admin-card">
            <h3>Order Status</h3>
            <p>Pending: <span style="color:#D97706; font-weight:600;">{{ $pendingOrders }}</span></p>
            <p>Completed: <span style="color:#059669; font-weight:600;">{{ $completedOrders }}</span></p>
            <br>
            <h3>Inventory Alerts</h3>
            <p>Low Stock: <span style="color:#D97706; font-weight:600;">{{ $lowStockProducts }}</span></p>
            <p>Out of Stock: <span style="color:#DC2626; font-weight:600;">{{ $outOfStockProducts }}</span></p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: {!! json_encode($chartValues) !!},
                backgroundColor: '#2563EB'
            }]
        }
    });
</script>
@endpush
