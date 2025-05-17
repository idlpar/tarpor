@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #008080;
            --primary-dark: #006666;
        }
    </style>
@endpush

@section('title', 'Tarpor Installer')

@section('content')
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-8">Welcome to Tarpor Installation</h1>
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <p class="text-gray-600 mb-4">This wizard will guide you through setting up your Tarpor application. Please ensure you have:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Database credentials (MySQL recommended).</li>
                    <li>Mail server details (SMTP or log).</li>
                    <li>Write permissions for the .env file.</li>
                </ul>
                <a href="{{ route('install.environment.form') }}" class="inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white font-semibold rounded-md hover:bg-[var(--primary-dark)]">
                    Start Installation
                </a>
            </div>
        </div>
    </section>
@endsection
