@extends('layouts.admin')

@section('title', 'Send Newsletter')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    @include('components.breadcrumbs', [
        'links' => [
            'Newsletter' => null
        ]
    ])
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Send Newsletter</h1>

    <div class="bg-white shadow-lg rounded-xl p-8">
        <form action="{{ route('admin.newsletter.send.post') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" id="subject" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required value="{{ old('subject') }}" placeholder="Enter newsletter subject">
                @error('subject')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Email Content (Markdown supported)</label>
                <textarea name="content" id="content" rows="10" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required placeholder="Enter your newsletter content here...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="send_to_all" id="send_to_all" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" checked>
                    <span class="ml-2 text-gray-800 font-medium">Send to all active subscribers</span>
                </label>
            </div>

            <div id="specific-emails-section" class="mb-6 hidden">
                <label for="specific_emails" class="block text-sm font-semibold text-gray-700 mb-2">Specific Emails (comma-separated)</label>
                <textarea name="specific_emails" id="specific_emails" rows="3" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="email1@example.com, email2@example.com">{{ old('specific_emails') }}</textarea>
                <p class="text-sm text-gray-500 mt-2">Leave empty or check "Send to all" to send to all active subscribers.</p>
                @error('specific_emails')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Send Newsletter</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sendToAllCheckbox = document.getElementById('send_to_all');
        const specificEmailsSection = document.getElementById('specific-emails-section');
        const specificEmailsTextarea = document.getElementById('specific_emails');

        function toggleSpecificEmailsSection() {
            if (sendToAllCheckbox.checked) {
                specificEmailsSection.classList.add('hidden');
                specificEmailsTextarea.removeAttribute('required');
            } else {
                specificEmailsSection.classList.remove('hidden');
                specificEmailsTextarea.setAttribute('required', 'required');
            }
        }

        sendToAllCheckbox.addEventListener('change', toggleSpecificEmailsSection);

        // Initial state on page load
        toggleSpecificEmailsSection();
    });
</script>
@endpush
