@extends('layouts.admin')

@section('title', 'Add New FAQ')

@section('admin_content')
    @php
        $breadcrumbs = [
            ['title' => 'FAQs', 'url' => route('faqs.index')],
            ['title' => 'Add New'],
        ];
    @endphp
    
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New FAQ</h1>
            <a href="{{ route('faqs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to FAQs
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
            <form action="{{ route('faqs.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                    <input type="text" name="question" id="question" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 @enderror" value="{{ old('question') }}" required>
                </div>
                <div class="mb-4">
                    <label for="answer" class="block text-sm font-medium text-gray-700">Answer</label>
                    <textarea id="answer" name="answer" rows="5" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answer') border-red-500 @enderror" placeholder="Enter FAQ answer" required>{{ old('answer') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection