@extends('layouts.admin')

@section('title', $brand->name . ' | Brand Details | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
        @include('components.breadcrumbs', [
            'links' => [
                'Brands' => route('brands.index'),
                $brand->name => null
            ]
        ])

        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800">{{ $brand->name }}</h2>
                <a href="{{ route('brands.edit', $brand->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">Edit Brand</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    @if($brand->logo)
                        <div class="mb-6 text-center">
                            <img src="{{ $brand->logo->url }}" alt="{{ $brand->name }} Logo" class="h-32 w-32 object-contain border p-2 rounded-md mx-auto">
                        </div>
                    @else
                        <div class="mb-6 text-center">
                            <div class="h-32 w-32 bg-gray-200 flex items-center justify-center rounded-md mx-auto">
                                <span class="text-gray-500">No Logo</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Brand Name</label>
                            <p class="text-gray-900 text-lg">{{ $brand->name }}</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Slug</label>
                            <p class="text-gray-900 bg-gray-50 p-2 rounded-md">{{ $brand->slug }}</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $brand->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $brand->description ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 border-t pt-6">
                <a href="{{ route('brands.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200">Back to Brands</a>
            </div>
        </div>
    </div>
@endsection
