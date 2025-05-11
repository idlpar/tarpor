@extends('layouts.app')
@section('content')
    <h1>{{ $product->name }}</h1>
    <p>Price: {{ number_format($product->price, 2) }} BDT</p>
    <p>{{ $product->description }}</p>
    @if ($relatedProducts->isNotEmpty())
        <h2>Related Products</h2>
        <ul>
            @foreach ($relatedProducts as $related)
                <li><a href="{{ route('product.view', $related->slug) }}" class="text-blue-600 hover:underline">{{ $related->name }}</a></li>
            @endforeach
        </ul>
    @endif
@endsection
