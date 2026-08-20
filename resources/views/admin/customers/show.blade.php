@extends('layouts.admin')

@section('title', 'Customer Details')
@section('header_title', 'Customer Detail')
@section('header_subtitle', 'Full profile view and history for ' . $customer->name)

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        &larr; Back to Customers List
    </a>
</div>

<div class="dashboard-row">
    <!-- Profile & Addresses -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Profile Card -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Customer Profile</h3>
            </div>
            <div class="details-list">
                <div class="details-item">
                    <span>Full Name</span>
                    <span>{{ $customer->name }}</span>
                </div>
                <div class="details-item">
                    <span>Mobile Number</span>
                    <span>{{ $customer->mobile_number }}</span>
                </div>
                <div class="details-item">
                    <span>Joined Date</span>
                    <span>{{ $customer->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="details-item">
                    <span>Total Orders</span>
                    <span>{{ $customer->orders->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Saved Addresses -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Delivery Addresses</h3>
            </div>
            
            <div class="address-grid">
                @forelse($customer->addresses as $address)
                    <div class="address-card">
                        <strong>Location: {{ $address->location }}</strong>
                        <p>{{ $address->address }}</p>
                        @if($address->landmark)
                            <p style="font-size: 0.8rem; font-style: italic;">Landmark: {{ $address->landmark }}</p>
                        @endif
                        <span class="pin">Pincode: {{ $address->pincode }}</span>
                    </div>
                @empty
                    <div style="color: var(--text-muted); font-size: 0.9rem;">No delivery addresses saved by this customer.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Order History</h3>
        </div>
        
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                        <tr>
                            <td><strong><a href="{{ route('admin.orders.show', $order->id) }}" style="color: var(--accent-indigo); text-decoration: none;">#ORD-{{ $order->id }}</a></strong></td>
                            <td>
                                <span class="badge {{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>₹{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'completed' ? 'success' : 'danger' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No orders found for this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
