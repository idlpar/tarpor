@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #008080;
            --primary-dark: #006666;
        }
    </style>
@endpush

@section('title', 'Database Setup')

@section('content')
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-8">Database Setup</h1>
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <p class="text-gray-600 mb-4">Selected Database: <strong>{{ ucfirst($db_connection ?? 'MySQL') }}</strong></p>
                <p class="text-gray-600 mb-4">Click the button below to set up your database. This will run migrations and seed initial data.</p>
                @if ($errors->has('database'))
                    <p class="text-red-600 mb-4">{{ $errors->first('database') }}</p>
                @endif
                <form action="{{ route('install.database') }}" method="POST">
                    @csrf
                    <input type="hidden" name="db_connection" value="{{ $db_connection ?? 'mysql' }}">
                    <div class="flex space-x-4">
                        <a href="{{ route('install.environment.form') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400">
                            Back to Environment
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white font-semibold rounded-md hover:bg-[var(--primary-dark)]">
                            Run Database Setup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
