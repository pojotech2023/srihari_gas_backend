@extends('layouts.admin')

@section('title', 'Edit Product')
@section('header_title', 'Edit Product')
@section('header_subtitle', 'Modify catalog details for ' . $product->name)

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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price (in ₹) *</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Delivery Charge Calculation (Trip Type) *</label>
            <div style="display: flex; gap: 1.5rem; background: rgba(255, 255, 255, 0.03); padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.5rem; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0; font-size: 0.9rem;">
                    <input type="radio" name="trip_type" value="two_way" {{ old('trip_type', $product->trip_type ?? 'two_way') === 'two_way' ? 'checked' : '' }} required>
                    <span><strong>Two Way</strong> <span style="color: var(--text-muted); font-size: 0.8rem;">(Round Trip: 10 KM + 10 KM × ₹12)</span></span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0; font-size: 0.9rem;">
                    <input type="radio" name="trip_type" value="one_way" {{ old('trip_type', $product->trip_type ?? 'two_way') === 'one_way' ? 'checked' : '' }} required>
                    <span><strong>One Way</strong> <span style="color: var(--text-muted); font-size: 0.8rem;">(Single: 10 KM × ₹12)</span></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Current Product Image</label>
            <div style="margin: 0.5rem 0;">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; border-radius: 0.5rem; object-fit: cover; border: 1px solid var(--border-color);">
            </div>
            
            <label for="image">Replace Product Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <small style="color: var(--text-muted); margin-top: 0.25rem;">Leave blank to keep current image. Supported formats: JPG, PNG, WEBP. Max size: 2MB.</small>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </div>
    </form>
</div>
@endsection
