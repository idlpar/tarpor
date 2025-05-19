@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $category->name }}</h1>
                    <div class="flex items-center mt-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($category->status) }}
                    </span>
                        @if($category->parent)
                            <span class="ml-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                        Parent: {{ $category->parent->name }}
                    </span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Edit
                    </a>
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Category Details</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Slug</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->slug }}</p>
                            </div>
                            @if($category->description)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $category->description }}</p>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Created</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($category->image)
                            <div class="bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-auto object-cover">
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($category->children->isNotEmpty())
                <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Subcategories ({{ $category->children->count() }})</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($category->children as $child)
                                <a href="{{ route('categories.show', $child->id) }}" class="group block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center">
                                            @if($child->image)
                                                <img class="h-10 w-10 rounded-md object-cover" src="{{ $child->image }}" alt="{{ $child->name }}">
                                            @else
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600">{{ $child->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $child->products_count ?? 0 }} products</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
