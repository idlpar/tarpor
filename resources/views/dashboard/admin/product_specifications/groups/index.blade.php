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
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $group->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $group->description ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
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
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.product_specifications.groups.edit', $group->id) }}" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit Group">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <span class="text-gray-300">|</span>
                                @if ($group->trashed())
                                    <form action="{{ route('admin.product_specifications.groups.restore', $group->id) }}" method="POST" class="inline-block restore-form" onsubmit="confirmRestore(event)">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900 custom-tooltip-trigger" data-tooltip="Restore Group">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.product_specifications.groups.destroy', $group->id) }}" method="POST" class="inline-block delete-form" onsubmit="confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Group">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
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
<script>
    function confirmDelete(event) {
        event.preventDefault(); // Prevent the form from submitting immediately
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true // Focus on the cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }

    function confirmRestore(event) {
        event.preventDefault(); // Prevent the form from submitting immediately
        Swal.fire({
            title: 'Are you sure?',
            text: "This will restore the item!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
            focusCancel: true // Focus on the cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }
</script>
@endpush
