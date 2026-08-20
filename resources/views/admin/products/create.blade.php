@extends('layouts.admin')

@section('title', 'Add New Product')
@section('header_title', 'Create Product')
@section('header_subtitle', 'Add a new product to the gas inventory catalog')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        &larr; Back to Products Catalog
    </a>
</div>

<div class="panel-card" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h3>Product Details</h3>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="e.g. 14.2kg Domestic Gas Cylinder" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price (in ₹) *</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="e.g. 950.00" step="0.01" min="0" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter product details, technical specs, or cylinder safety guidelines...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <small style="color: var(--text-muted); margin-top: 0.25rem;">Supported formats: JPG, PNG, WEBP. Max size: 2MB.</small>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Product</button>
        </div>
    </form>
</div>
@endsection
