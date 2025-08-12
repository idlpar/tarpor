@extends('layouts.admin')

@section('title', 'Manage Product Specification Groups')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Product Specification Groups' => null
            ]
        ])

        <x-ui.page-header title="Product Specification Groups" description="Manage groups of specifications for your products.">
            <x-ui.search-box />
            <a href="{{ route('admin.product_specifications.groups.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Group</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['Name', 'Description', 'Status', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse ($groups as $group)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $group->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $group->description ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @if ($group->trashed())
                                <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Trashed</span>
                                </span>
                            @else
                                <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Active</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                            <x-ui.table-actions :item="$group" baseRoute="admin.product_specifications.groups" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No product specification groups found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $groups->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>
@endsection

@push('scripts')
@endpush