@extends('layouts.admin')

@section('title', 'Edit Newsletter Subscriber')

@section('admin_content')
    <div class="container mx-auto px-4 py-8">
        @include('components.breadcrumbs', ['links' => $links])

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.newsletter.subscribers.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Newsletter Subscriber</h1>
                        <p class="mt-1 text-sm text-gray-600">Modify the details of the newsletter subscriber</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.newsletter.subscribers.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All Subscribers
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('admin.newsletter.subscribers.update', $subscriber->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $subscriber->email) }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="is_subscribed" class="block text-sm font-medium text-gray-700">Subscription Status</label>
                        <select name="is_subscribed" id="is_subscribed" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="1" {{ old('is_subscribed', $subscriber->is_subscribed) == 1 ? 'selected' : '' }}>Subscribed</option>
                            <option value="0" {{ old('is_subscribed', $subscriber->is_subscribed) == 0 ? 'selected' : '' }}>Unsubscribed</option>
                        </select>
                        @error('is_subscribed')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                        Update Subscriber
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
