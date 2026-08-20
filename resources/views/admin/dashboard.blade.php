@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Sri Hari Gas Agent Overview & Analytics')

@section('content')
<!-- Metrics Overview -->
<div class="metrics-grid">
    <div class="metric-card indigo">
        <div class="metric-details">
            <p>Total Customers</p>
            <h2>{{ $totalCustomers }}</h2>
        </div>
        <div class="metric-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>
    
    <div class="metric-card violet">
        <div class="metric-details">
            <p>Products</p>
            <h2>{{ $totalProducts }}</h2>
        </div>
        <div class="metric-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
    </div>
    
    <div class="metric-card warning">
        <div class="metric-details">
            <p>Total Orders</p>
            <h2>{{ $totalOrders }}</h2>
        </div>
        <div class="metric-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
    </div>
    
    <div class="metric-card success">
        <div class="metric-details">
            <p>Total Revenue</p>
            <h2>₹{{ number_format($totalRevenue, 2) }}</h2>
        </div>
        <div class="metric-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<!-- Main Panels Row -->
<div class="dashboard-row">
    <!-- Recent Orders Table -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View All</a>
        </div>
        
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>#ORD-{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                <span class="badge {{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>₹{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted);">No orders recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Visual Status Chart -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Order Status Breakdown</h3>
        </div>
        <div style="position: relative; height:240px; width:100%; display: flex; justify-content: center; align-items: center;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        
        const data = {
            labels: ['Pending', 'Assigned', 'Delivered', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $orderStatusStats['pending'] }},
                    {{ $orderStatusStats['assigned'] }},
                    {{ $orderStatusStats['delivered'] }},
                    {{ $orderStatusStats['cancelled'] }}
                ],
                backgroundColor: [
                    '#f59e0b', // Pending (Warning Orange)
                    '#0ea5e9', // Assigned (Info Sky Blue)
                    '#10b981', // Delivered (Success Emerald)
                    '#ef4444'  // Cancelled (Danger Red)
                ],
                borderWidth: 1,
                borderColor: 'rgba(255, 255, 255, 0.1)'
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: {
                                family: 'Outfit',
                                size: 12
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        };

        new Chart(ctx, config);
    });
</script>
@endsection
