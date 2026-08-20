@extends('layouts.admin')

@section('title', 'Customer Management')
@section('header_title', 'Customers')
@section('header_subtitle', 'View registered user signups and engagement details')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3>Registered Customers</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">Total Customers: {{ $customers->count() }}</span>
    </div>
    
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Mobile Number</th>
                    <th>Orders Placed</th>
                    <th>Date Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->mobile_number }}</td>
                        <td>
                            <span class="badge" style="background-color: rgba(99, 102, 241, 0.1); color: var(--accent-indigo);">
                                {{ $customer->orders_count }} orders
                            </span>
                        </td>
                        <td>{{ $customer->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No customers registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
