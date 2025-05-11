@extends('layouts.app')
@section('content')
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
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
                <td>{{ $product->sku }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->status }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-info">View</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
@endsection
