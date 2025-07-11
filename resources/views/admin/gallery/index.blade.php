@extends('layouts.admin')

@section('title', 'Media Gallery')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Media Gallery</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-gray-700">This is where your media gallery will be displayed.</p>
        <p class="text-gray-600 mt-2">The frontend JavaScript needs to be implemented to interact with the backend API endpoints for file and folder management.</p>
        <p class="text-gray-600 mt-2">You can start by integrating a media manager library or building a custom one that consumes the JSON data from the <code>GalleryController</code>'s API methods.</p>
    </div>
</div>
@endsection
