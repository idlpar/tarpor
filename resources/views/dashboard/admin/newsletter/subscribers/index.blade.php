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
                <x-ui.table :headers="['Email', 'Status', 'Subscribed At', 'Actions']">
                    @forelse ($subscribers as $subscriber)
                        <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $subscriber->email }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subscriber->is_subscribed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $subscriber->is_subscribed ? 'Subscribed' : 'Unsubscribed' }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $subscriber->created_at->format('M d, Y H:i A') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                                <x-ui.table-actions
                                    :item="$subscriber"
                                    baseRoute="admin.newsletter.subscribers"
                                    :showToggle="true"
                                    :toggleRoute="route('admin.newsletter.subscribers.toggle-status', $subscriber->id)"
                                    :isToggled="$subscriber->is_subscribed"
                                    toggleTitle="Confirm Status Change?"
                                    toggleText="Are you sure you want to change the subscription status for this subscriber?"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                                No newsletter subscribers found.
                            </td>
                        </tr>
                    @endforelse
                </x-ui.table>
            </div>

            <div class="mt-4 flex justify-end">
                {{ $subscribers->links('components.ui.custom-pagination') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- The confirmAction function is now handled within table-actions.blade.php --}}
@endpush