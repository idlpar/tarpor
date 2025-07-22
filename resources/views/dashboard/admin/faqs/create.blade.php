@extends('layouts.admin')

@section('title', 'Add New FAQ')

@section('admin_content')
    <div class="container mx-auto">
        @include('components.breadcrumbs', [
            'links' => [
                'FAQs' => route('faqs.index'),
                'Add New' => null
            ]
        ])

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('faqs.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Add New FAQ</h1>
                        <p class="mt-1 text-sm text-gray-600">Add a new frequently asked question</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('faqs.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All FAQs
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-700 p-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('faqs.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="question" class="block text-sm font-semibold text-gray-700 mb-2">Question *</label>
                    <input type="text" name="question" id="question" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" value="{{ old('question') }}" required placeholder="Enter the FAQ question">
                    @error('question')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="answer" class="block text-sm font-semibold text-gray-700 mb-2">Answer *</label>
                    <textarea id="answer" name="answer" rows="5" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Enter FAQ answer" required>{{ old('answer') }}</textarea>
                    @error('answer')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                        Add FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
