@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                @if($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $category->parent->slug) }}">
                            {{ $category->parent->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="display-5 mb-3">{{ $category->name }}</h1>
                @if($category->description)
                    <div class="lead mb-4">{!! $category->description !!}</div>
                @endif
            </div>
            <div class="col-lg-4">
                @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}"
                         class="img-fluid rounded shadow">
                @endif
            </div>
        </div>

        @if($category->children->isNotEmpty())
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="mb-4">Subcategories</h3>
                    <div class="row">
                        @foreach($category->children as $child)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">
                                            <a href="{{ route('categories.show', $child->slug) }}"
                                               class="text-decoration-none">
                                                {{ $child->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-0">
                                            {{ $child->products_count ?? 0 }} products
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Products</h3>
                    <div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active">All</button>
                            <button type="button" class="btn btn-outline-secondary">Featured</button>
                            <button type="button" class="btn btn-outline-secondary">New Arrivals</button>
                        </div>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-3 mb-4">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No products found in this category.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
