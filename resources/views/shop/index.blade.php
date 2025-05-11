@extends('layouts.app')
@section('content')
    <h1>Shop</h1>
    <div class="products grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach ($products as $product)
            <div class="product border p-4 rounded">
                <a href="{{ route('product.view', $product->slug) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a>
                <p>{{ number_format($product->price, 2) }} BDT</p>
            </div>
        @endforeach
    </div>
    {{ $products->links() }}
@endsection
