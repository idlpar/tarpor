@extends('layouts.app')

@section('title', 'Edit Product Attribute | ' . strtoupper(config('app.name')))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Display Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Product Attributes' => route('product_attributes.index'),
                'Edit Attribute' => null
            ]
        ])

        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Product Attribute</h1>

            <form action="{{ route('product_attributes.update', $product_attribute->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="block font-semibold text-gray-700 mb-2">Attribute Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product_attribute->name) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="e.g., Size, Color, Material">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Update Attribute
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
