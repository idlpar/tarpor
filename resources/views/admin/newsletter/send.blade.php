@extends('layouts.admin')

@section('title', 'Send Newsletter')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Send Newsletter</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.newsletter.send.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required value="{{ old('subject') }}">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Email Content (Markdown supported)</label>
                <textarea name="content" id="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="send_to_all" id="send_to_all" class="form-checkbox h-5 w-5 text-blue-600" checked>
                    <span class="ml-2 text-gray-700">Send to all active subscribers</span>
                </label>
            </div>

            <div id="specific-emails-section" class="mb-6 hidden">
                <label for="specific_emails" class="block text-sm font-medium text-gray-700">Specific Emails (comma-separated)</label>
                <textarea name="specific_emails" id="specific_emails" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="email1@example.com, email2@example.com">{{ old('specific_emails') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Leave empty or check "Send to all" to send to all active subscribers.</p>
                @error('specific_emails')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Send Newsletter</button>
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
