@extends('layouts.admin')

@section('title', 'Edit Delivery Agent')
@section('header_title', 'Edit Delivery Agent')
@section('header_subtitle', 'Modify details for ' . $deliveryman->name)

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.deliverymen.index') }}" class="btn btn-secondary" style="text-decoration: none;">
        &larr; Back to Delivery Agents
    </a>
</div>

<div class="panel-card" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h3>Agent Details</h3>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.deliverymen.update', $deliveryman->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Agent Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $deliveryman->name) }}" required>
        </div>

        <div class="form-group">
            <label for="mobile_number">Mobile Number *</label>
            <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{ old('mobile_number', $deliveryman->mobile_number) }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep unchanged">
            <small style="color: var(--text-muted); margin-top: 0.25rem;">Minimum 6 characters. Only fill if you wish to reset the agent's password.</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Leave blank to keep unchanged">
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <a href="{{ route('admin.deliverymen.index') }}" class="btn btn-secondary" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Agent</button>
        </div>
    </form>
</div>
@endsection
