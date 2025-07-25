@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')

@section('admin_content')
    <div class="container mx-auto px-4 py-8">
        @include('components.breadcrumbs', ['links' => ['Newsletter Subscribers' => route('admin.newsletter.subscribers.index')]])

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Newsletter Subscribers</h1>
            <a href="{{ route('admin.newsletter.send') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Send Newsletter
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Subscribed At</th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $subscriber->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subscriber->is_subscribed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subscriber->is_subscribed ? 'Subscribed' : 'Unsubscribed' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscriber->created_at->format('M d, Y H:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.newsletter.subscribers.edit', $subscriber->id) }}" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit Subscriber">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.newsletter.subscribers.toggle-status', $subscriber->id) }}" method="POST" class="inline-block toggle-status-form" onsubmit="confirmAction(event, 'toggle')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-purple-600 hover:text-purple-900 custom-tooltip-trigger" data-tooltip="Toggle Status">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                            </button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.newsletter.subscribers.destroy', $subscriber->id) }}" method="POST" class="inline-block delete-form" onsubmit="confirmAction(event, 'delete')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Subscriber">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h2 class="text-xl font-semibold text-gray-700">No Subscribers Found</h2>
                                        <p class="text-gray-500 mt-1">No one has subscribed to your newsletter yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $subscribers->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@push('scripts')
<script>
    function confirmAction(event, type) {
        event.preventDefault(); // Prevent the form from submitting immediately
        let title = '';
        let text = '';
        let icon = '';
        let confirmButtonText = '';

        if (type === 'delete') {
            title = 'Are you sure?';
            text = "You won't be able to revert this!";
            icon = 'warning';
            confirmButtonText = 'Yes, delete it!';
        } else if (type === 'toggle') {
            const isSubscribed = event.target.querySelector('button').closest('tr').querySelector('.rounded-full').textContent.trim() === 'Subscribed';
            const actionText = isSubscribed ? 'unsubscribe' : 'subscribe';
            title = `Confirm ${actionText}?`;
            text = `Are you sure you want to ${actionText} this subscriber?`;
            icon = 'question';
            confirmButtonText = `Yes, ${actionText} them!`;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            focusCancel: true // Focus on the cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }
</script>
@endpush
@endpush
