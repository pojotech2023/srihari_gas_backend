@extends('layouts.admin')

@section('title', 'Order Details')
@section('header_title', 'Order Details')
@section('header_subtitle', 'Review purchase items, payment gateways, and delivery details')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        &larr; Back to Orders Queue
    </a>
</div>

<div class="dashboard-row">
    <!-- Items & Transactions -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Order Items -->
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
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: 700; border-top: 1px solid var(--border-color);">Subtotal:</td>
                            <td style="font-weight: 700; border-top: 1px solid var(--border-color); color: var(--accent-indigo); font-size: 1.1rem;">
                                ₹{{ number_format($order->total_price, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Callback Details -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Payment Callback Logs</h3>
            </div>
            @if($order->transaction)
                <div class="details-list">
                    <div class="details-item">
                        <span>Transaction ID</span>
                        <span style="color: var(--accent-indigo);">{{ $order->transaction->transaction_id }}</span>
                    </div>
                    <div class="details-item">
                        <span>Payment Gateway</span>
                        <span style="text-transform: uppercase;">{{ $order->transaction->payment_gateway }}</span>
                    </div>
                    <div class="details-item">
                        <span>Callback Status</span>
                        <span class="badge {{ $order->transaction->status === 'success' || $order->transaction->status === 'completed' ? 'success' : 'danger' }}">
                            {{ $order->transaction->status }}
                        </span>
                    </div>
                    <div class="details-item">
                        <span>Gateway Amount Received</span>
                        <span>₹{{ number_format($order->transaction->amount, 2) }}</span>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Raw Callback Details (Payload JSON)</label>
                    <pre style="background-color: rgba(0, 0, 0, 0.4); border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; color: #10b981; font-family: monospace; font-size: 0.8rem; overflow-x: auto; max-height: 200px;">{{ json_encode($order->transaction->payload, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @else
                <div style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 1.5rem; border: 1px dashed var(--border-color); border-radius: 0.5rem;">
                    No payment callback transaction logged for this order yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Info & Actions Panel -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Order Stats Info -->
        <div class="panel-card">
            <div class="panel-header">
                <h3>Order Status</h3>
            </div>
            
            <div class="details-list">
                <div class="details-item">
                    <span>Order Date</span>
                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="details-item">
                    <span>Payment Type</span>
                    <strong>{{ $order->payment_method === 'online' ? 'Online Payment (Razorpay)' : 'Cash on Delivery' }}</strong>
                </div>
                @if($order->payment_method === 'online' && $order->transaction)
                <div class="details-item">
                    <span>Razorpay ID</span>
                    <span style="color: var(--accent-indigo); font-family: monospace;">{{ $order->transaction->transaction_id }}</span>
                </div>
                @endif
                <div class="details-item">
                    <span>Current Status</span>
                    <span class="badge {{ $order->status }}">{{ $order->status }}</span>
                </div>
                <div class="details-item">
                    <span>Payment Status</span>
                    <span class="badge {{ $order->payment_status === 'completed' ? 'success' : 'danger' }}">{{ $order->payment_status }}</span>
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
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->payment_status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
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
