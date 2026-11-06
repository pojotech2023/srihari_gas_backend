@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'BookMyLPG Overview & Analytics')

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
            <h2>₹{{ number_format(round($totalRevenue)) }}</h2>
        </div>
        <div class="metric-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<!-- Order Status Breakdown Panel -->
<div class="panel-card" style="margin-top: 1.5rem;">
    <div class="panel-header">
        <div>
            <h3>Order Status Breakdown</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem;">Live tracking of order fulfillment lifecycle</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
            Manage Orders Queue &rarr;
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; padding: 1rem 0;">
        <div style="position: relative; height: 260px; width: 100%; display: flex; justify-content: center; align-items: center;">
            <canvas id="orderStatusChart"></canvas>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #f59e0b;"></span>
                    <strong style="color: #f59e0b;">Pending Orders</strong>
                </div>
                <span style="font-size: 1.15rem; font-weight: 700; color: #f59e0b;">{{ $orderStatusStats['pending'] }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(14, 165, 233, 0.1); border: 1px solid rgba(14, 165, 233, 0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #0ea5e9;"></span>
                    <strong style="color: #0ea5e9;">Assigned for Delivery</strong>
                </div>
                <span style="font-size: 1.15rem; font-weight: 700; color: #0ea5e9;">{{ $orderStatusStats['assigned'] }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #10b981;"></span>
                    <strong style="color: #10b981;">Delivered Orders</strong>
                </div>
                <span style="font-size: 1.15rem; font-weight: 700; color: #10b981;">{{ $orderStatusStats['delivered'] }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #ef4444;"></span>
                    <strong style="color: #ef4444;">Cancelled Orders</strong>
                </div>
                <span style="font-size: 1.15rem; font-weight: 700; color: #ef4444;">{{ $orderStatusStats['cancelled'] }}</span>
            </div>
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
