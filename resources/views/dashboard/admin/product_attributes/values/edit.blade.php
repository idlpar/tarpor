@extends('layouts.app')

@section('title', 'Edit Attribute Value | ' . strtoupper(config('app.name')))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Product Attributes' => route('product_attributes.index'),
                $attribute->name => route('product_attributes.edit', $attribute->id),
                'Edit Value' => null
            ]
        ])

        <div class="max-w-xl mx-auto bg-input-bg p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold mb-6 text-text-dark">Edit Value for {{ $attribute->name }}</h1>

            <form action="{{ route('product_attributes.values.update', ['product_attribute' => $attribute->id, 'value' => $value->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="value" class="block font-semibold text-text-dark mb-2">Value *</label>
                    <input type="text" id="value" name="value" value="{{ old('value', $value->value) }}" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('value') border-error @enderror" placeholder="e.g., Red, Small, Cotton">
                    @error('value')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors duration-200">
                        Update Value
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
