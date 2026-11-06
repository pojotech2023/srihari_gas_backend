@extends('layouts.admin')

@section('title', 'Order Details')
@section('header_title', 'Order Details')
@section('header_subtitle', 'Review purchase items, payment status, and delivery dispatch')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        &larr; Back to Orders Queue
    </a>
</div>

<div class="dashboard-row">
    <!-- Ordered Items Panel -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="panel-card">
            <div class="panel-header">
                <h3>Ordered Items</h3>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Item Image</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="product-thumbnail">
                                </td>
                                <td><strong>{{ $item->product->name }}</strong></td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                            </tr>
                        @endforeach
                        @php
                            $itemsSubtotal = $order->items->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                            $isOneWay = ($order->trip_type ?? 'two_way') === 'one_way';
                        @endphp
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 600; border-top: 1px solid var(--border-color);">Items Subtotal:</td>
                            <td style="font-weight: 600; border-top: 1px solid var(--border-color); color: var(--text-primary); font-size: 1rem;">
                                ₹{{ number_format(round($itemsSubtotal)) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 600;">
                                Delivery Charge
                                @if(($order->distance_km ?? 0) > 0)
                                    <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">
                                        @if($isOneWay)
                                            ({{ $order->distance_km }} KM one-way @ ₹12/KM)
                                        @else
                                            ({{ $order->distance_km }} KM one-way, {{ $order->round_trip_km ?? ($order->distance_km * 2) }} KM round-trip @ ₹12/KM)
                                        @endif
                                    </span>
                                @endif:
                            </td>
                            <td style="font-weight: 600; color: #10b981; font-size: 1rem;">
                                ₹{{ number_format(round($order->delivery_charge ?? 0)) }}
                            </td>
                        </tr>
                        <tr style="background-color: rgba(255, 255, 255, 0.03);">
                            <td colspan="4" style="text-align: right; font-weight: 700; border-top: 2px solid var(--border-color);">Total Amount:</td>
                            <td style="font-weight: 700; border-top: 2px solid var(--border-color); color: var(--accent-indigo); font-size: 1.15rem;">
                                ₹{{ number_format(round($order->total_price)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Info & Actions Panel -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Order Status Info -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Order Status</h3>
            </div>
            
            <div class="details-list">
                <div class="details-item">
                    <span>Order Date & Time</span>
                    <strong>{{ $order->created_at->format('M d, Y h:i A') }}</strong>
                </div>
                @if($order->assigned_at)
                <div class="details-item">
                    <span>Assigned Time</span>
                    <strong style="color: #10b981;">{{ $order->assigned_at->format('M d, Y h:i A') }}</strong>
                </div>
                @endif
                <div class="details-item">
                    <span>Payment Type</span>
                    <strong>{{ $order->payment_method === 'online' ? 'Online Payment (Razorpay)' : 'Cash on Delivery' }}</strong>
                </div>
                <div class="details-item">
                    <span>Current Status</span>
                    <span class="badge {{ $order->status }}">{{ $order->status }}</span>
                </div>
                <div class="details-item">
                    <span>Payment Status</span>
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
                    <span class="badge" style="{{ $badgeStyle }} text-transform: capitalize; padding: 0.25rem 0.6rem; font-size: 0.8rem; border-radius: 4px;">{{ $pStatus }}</span>
                </div>
                @if(($order->distance_km ?? 0) > 0)
                <div class="details-item">
                    <span>Delivery Distance</span>
                    <strong>{{ $order->distance_km }} KM <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">({{ ($order->trip_type ?? 'two_way') === 'one_way' ? 'One Way' : 'Round Trip: ' . ($order->round_trip_km ?? ($order->distance_km * 2)) . ' KM' }})</span></strong>
                </div>
                @endif
                <div class="details-item">
                    <span>Delivery Charge</span>
                    <strong style="color: #10b981;">₹{{ number_format(round($order->delivery_charge ?? 0)) }}</strong>
                </div>
                <div class="details-item">
                    <span>Total Amount</span>
                    <strong style="color: var(--accent-indigo); font-size: 1.15rem;">₹{{ number_format(round($order->total_price)) }}</strong>
                </div>
            </div>

            <!-- Update Status Form -->
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="margin-top: 1rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                @csrf
                <div class="form-group">
                    <label for="status">Change Order Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ $order->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in progress" {{ $order->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="cylinder delivered" {{ $order->status === 'cylinder delivered' ? 'selected' : '' }}>Cylinder Delivered</option>
                        <option value="i got empty cylinder" {{ $order->status === 'i got empty cylinder' ? 'selected' : '' }}>I Got Empty Cylinder</option>
                        <option value="cylinder delivered in godown" {{ $order->status === 'cylinder delivered in godown' ? 'selected' : '' }}>Cylinder Delivered in Godown</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_status">Change Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-control" required>
                        <option value="pending" {{ ($order->payment_status === 'pending') ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ ($order->payment_status === 'paid' || $order->payment_status === 'completed') ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ ($order->payment_status === 'unpaid') ? 'selected' : '' }}>Unpaid</option>
                        <option value="failed" {{ ($order->payment_status === 'failed') ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 0.5rem;">
                    Update Statuses
                </button>
            </form>
        </div>

        <!-- Customer & Dispatch -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Customer & Delivery</h3>
            </div>
            
            <div class="details-list">
                <div class="details-item">
                    <span>Customer</span>
                    <strong>{{ $order->user->name }}</strong>
                </div>
                <div class="details-item">
                    <span>Mobile</span>
                    <span>{{ $order->user->mobile_number }}</span>
                </div>
            </div>

            <div style="margin-top: 1rem; background-color: rgba(255, 255, 255, 0.02); padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                <strong style="color: var(--accent-indigo); font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">Shipping Address</strong>
                <p style="font-size: 0.85rem; line-height: 1.4;">
                    {{ $order->address->address }}<br>
                    <strong>Location:</strong> {{ $order->address->location }}<br>
                    @if($order->address->landmark)
                        <strong>Landmark:</strong> {{ $order->address->landmark }}<br>
                    @endif
                    <strong>Pincode:</strong> {{ $order->address->pincode }}
                </p>
            </div>

            <!-- Assigned Deliveryman Section -->
            <div style="margin-top: 1rem;">
                <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Deliveryman Assignment</label>
                @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                    <form action="{{ route('admin.orders.assign', $order->id) }}" method="POST">
                        @csrf
                        <div style="display: flex; gap: 0.25rem;">
                            <select name="deliveryman_id" class="form-control" style="flex-grow: 1;" required>
                                <option value="">Select Deliveryman</option>
                                @foreach($deliverymen as $man)
                                    <option value="{{ $man->id }}" {{ $order->deliveryman_id == $man->id ? 'selected' : '' }}>
                                        {{ $man->name }} ({{ $man->mobile_number }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                                Assign
                            </button>
                        </div>
                    </form>
                @else
                    @if($order->deliveryman)
                        <div class="details-list" style="background-color: rgba(255, 255, 255, 0.02); padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                            <div class="details-item" style="border: none; padding: 0;">
                                <span>Assigned Driver</span>
                                <strong>{{ $order->deliveryman->name }}</strong>
                            </div>
                            <div class="details-item" style="border: none; padding: 0; margin-top: 0.25rem;">
                                <span>Driver Mobile</span>
                                <span>{{ $order->deliveryman->mobile_number }}</span>
                            </div>
                            @if($order->assigned_at)
                            <div class="details-item" style="border: none; padding: 0; margin-top: 0.25rem;">
                                <span>Assigned Time</span>
                                <span style="color: #10b981;">{{ $order->assigned_at->format('M d, Y h:i A') }}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">No deliveryman was assigned.</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
