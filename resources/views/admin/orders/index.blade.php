@extends('layouts.admin')

@section('title', 'Order Management')
@section('header_title', 'Orders')
@section('header_subtitle', 'Review customer purchases, update states, and dispatch deliveries')

@section('content')
<!-- Status Filtering Tabs -->
<div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
    <a href="{{ route('admin.orders.index') }}" class="btn {{ !$status ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
        All Orders
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
        Pending
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'assigned']) }}" class="btn {{ $status === 'assigned' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
        Assigned
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn {{ $status === 'delivered' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
        Delivered
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn {{ $status === 'cancelled' ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
        Cancelled
    </a>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h3>Orders Queue</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">Showing {{ $orders->count() }} orders</span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer & Address</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Deliveryman</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#ORD-{{ $order->id }}</strong></td>
                        <td>
                            <strong>{{ $order->user->name }}</strong> ({{ $order->user->mobile_number }})<br>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $order->address->address }}, {{ $order->address->location }} - {{ $order->address->pincode }}</span>
                        </td>
                        <td>
                            <strong>₹{{ number_format(round($order->total_price)) }}</strong><br>
                            <span style="font-size: 0.8rem; color: #10b981; font-weight: 500;">
                                Delivery: ₹{{ number_format(round($order->delivery_charge ?? 0)) }}
                                @if(($order->distance_km ?? 0) > 0)
                                    <span style="color: var(--text-muted); font-size: 0.75rem;">
                                        ({{ $order->distance_km }} KM {{ ($order->trip_type ?? 'two_way') === 'one_way' ? 'one-way' : 'round-trip' }})
                                    </span>
                                @endif
                            </span>
                        </td>
                        <td>
                            Order: <span class="badge {{ $order->status }}">{{ $order->status }}</span><br>
                            <div style="margin-top: 0.35rem;">
                                @php
                                    $pStatus = $order->payment_status === 'completed' ? 'paid' : ($order->payment_status ?: 'pending');
                                    $badgeStyle = match($pStatus) {
                                        'paid' => 'background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);',
                                        'pending' => 'background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);',
                                        'unpaid' => 'background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);',
                                        'failed' => 'background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);',
                                        default => 'background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);'
                                    };
                                @endphp
                                Payment: <span class="badge" style="{{ $badgeStyle }} text-transform: capitalize; padding: 0.2rem 0.5rem; font-size: 0.75rem; border-radius: 4px;">{{ $pStatus }}</span>
                            </div>
                        </td>
                        <td>
                            @if($order->deliveryman)
                                <span style="font-weight: 500; color: var(--accent-violet);">{{ $order->deliveryman->name }}</span><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->deliveryman->mobile_number }}</span>
                                @if($order->assigned_at)
                                    <br><span style="font-size: 0.7rem; color: #10b981;">Assigned: {{ $order->assigned_at->format('M d, h:i A') }}</span>
                                @endif
                            @else
                                <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">Unassigned</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <strong>{{ $order->created_at->format('h:i A') }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">No orders found matching this status.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
