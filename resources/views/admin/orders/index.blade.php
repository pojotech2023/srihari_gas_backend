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
                    <!-- <th style="width: 220px;">Quick Assign</th> -->
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
                        <td><strong>₹{{ number_format($order->total_price, 2) }}</strong></td>
                        <td>
                            Order: <span class="badge {{ $order->status }}">{{ $order->status }}</span><br>
                            <div style="margin-top: 0.25rem;">
                                Payment: <span class="badge {{ $order->payment_status === 'completed' ? 'success' : 'danger' }}">{{ $order->payment_status }}</span>
                            </div>
                        </td>
                        <td>
                            @if($order->deliveryman)
                                <span style="font-weight: 500; color: var(--accent-violet);">{{ $order->deliveryman->name }}</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">Unassigned</span>
                            @endif
                        </td>
                       
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 2rem;">No orders found matching this status.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
