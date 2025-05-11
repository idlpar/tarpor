@extends('layouts.app')
@section('content')
    <h1>Categories</h1>
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($categories as $category)
            <li><a href="{{ route('public.categories.show', $category->slug) }}" class="text-blue-600 hover:underline">{{ $category->name }}</a></li>
        @endforeach
    </ul>
@endsection
