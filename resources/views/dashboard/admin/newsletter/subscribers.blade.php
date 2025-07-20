@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    @include('components.breadcrumbs', [
        'links' => [
            'Newsletter Subscribers' => null
        ]
    ])
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Newsletter Subscribers</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Subscriber List</h2>
            @if($subscribers->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-600 text-lg">No newsletter subscribers found yet.</p>
                    <p class="text-gray-500 mt-2">Encourage users to sign up for your amazing content!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subscribers as $subscriber)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subscriber->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($subscriber->is_subscribed)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Subscribed</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unsubscribed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection