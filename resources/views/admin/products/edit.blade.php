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
