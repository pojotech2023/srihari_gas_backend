@extends('layouts.admin')

@section('title', 'Product Management')
@section('header_title', 'Products')
@section('header_subtitle', 'Add, modify, and delete inventory products')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3>Product Catalog</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-thumbnail">
                        </td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>
                            <strong style="color: var(--success);">₹{{ number_format($product->price, 2) }}</strong>
                        </td>
                        <td style="max-width: 300px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            {{ $product->description ?? 'No description provided.' }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No products in catalog. Click "Add New Product" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
