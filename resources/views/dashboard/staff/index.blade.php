@extends('layouts.app')

@section('content')
    <h1>Staff Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <h2>Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-4">Add Product</a>
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku ?? 'N/A' }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->status }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
@endsection
