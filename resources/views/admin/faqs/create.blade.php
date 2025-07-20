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
            <h1 class="text-2xl font-bold text-text-dark">Add New FAQ</h1>
            <a href="{{ route('faqs.index') }}" class="inline-flex items-center px-4 py-2 border border-input-border rounded-md shadow-sm text-sm font-medium text-text-dark bg-input-bg hover:bg-bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Back to FAQs
            </a>
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

        <div class="bg-white shadow-sm rounded-lg p-6 border border-input-border">
            <form action="{{ route('faqs.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="question" class="block text-sm font-medium text-text-dark">Question</label>
                    <input type="text" name="question" id="question" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('question') border-error @enderror" value="{{ old('question') }}" required>
                </div>
                <div class="mb-4">
                    <label for="answer" class="block text-sm font-medium text-text-dark">Answer</label>
                    <textarea id="answer" name="answer" rows="5" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('answer') border-error @enderror" placeholder="Enter FAQ answer" required>{{ old('answer') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Add FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection