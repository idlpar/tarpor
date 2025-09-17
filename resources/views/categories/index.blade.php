@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Product Categories</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Browse through our carefully curated product categories to find exactly what you're looking for.</p>
        </div>

        <div class="mb-8">
            <form action="{{ route('categories.index') }}" method="GET">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search categories..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="{{ request('search') }}">
                    <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="group relative block overflow-hidden rounded-xl bg-white shadow-md hover:shadow-lg transition duration-300">
                    <div class="relative h-48 overflow-hidden">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-4">
                            <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                            @if($category->children->isNotEmpty())
                                <p class="text-sm text-blue-200 mt-1">
                                    {{ $category->children->count() }} subcategories
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($category->description)
                        <div class="p-4">
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $category->description }}</p>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endsection
