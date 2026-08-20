@extends('layouts.admin')

@section('title', 'Deliveryman Management')
@section('header_title', 'Delivery Personnel')
@section('header_subtitle', 'Manage drivers and dispatch delivery agents')

@section('content')
<div class="dashboard-row">
    <!-- Deliverymen List -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Registered Delivery Agents</h3>
            <span style="font-size: 0.85rem; color: var(--text-muted);">Total Agents: {{ $deliverymen->count() }}</span>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Agent Name</th>
                        <th>Mobile Number</th>
                        <th>Assigned Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliverymen as $man)
                        <tr>
                            <td><strong>{{ $man->name }}</strong></td>
                            <td>{{ $man->mobile_number }}</td>
                            <td>
                                <span class="badge" style="background-color: rgba(139, 92, 246, 0.1); color: var(--accent-violet);">
                                    {{ $man->orders->count() }} orders
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('admin.deliverymen.edit', $man->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; text-decoration: none;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.deliverymen.destroy', $man->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this delivery agent?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">No delivery agents registered. Use the panel on the right to add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Deliveryman Form -->
    <div class="panel-card" style="height: fit-content;">
        <div class="panel-header">
            <h3>Add Delivery Agent</h3>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.deliverymen.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Agent Name *</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Ramesh Kumar" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number *</label>
                <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="e.g. 9876543222" value="{{ old('mobile_number') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                Add Agent
            </button>
        </form>
    </div>
</div>
@endsection
