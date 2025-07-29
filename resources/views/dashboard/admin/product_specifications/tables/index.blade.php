@extends('layouts.admin')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.ui.breadcrumbs', [
            'links' => [
                'Product Specifications' => route('admin.product_specifications.groups.index'),
                'Tables' => null
            ]
        ])

        <x-ui.page-header title="Product Specification Tables" description="Manage tables for your product specifications.">
            <x-ui.search-box />
            <a href="{{ route('admin.product_specifications.tables.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Table</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['Name', 'Type', 'Groups', 'Status', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse ($tables as $table)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ $table->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            {{ ucfirst($table->type) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @forelse($table->groups as $group)
                                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{ $group->name }}</span>
                            @empty
                                N/A
                            @endforelse
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            @if ($table->trashed())
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
                            <x-ui.table-actions :item="$table" baseRoute="admin.product_specifications.tables" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No product specification tables found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $tables->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>
@endsection

@push('scripts')
<script>
    // The confirmDelete and confirmRestore functions are now part of table-actions.blade.php
    // No need to duplicate them here.
</script>
@endpush