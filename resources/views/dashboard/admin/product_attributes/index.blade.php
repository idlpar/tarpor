@extends('layouts.admin')

@section('title', 'Manage Product Attributes')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.ui.breadcrumbs', [
            'links' => [
                'Product Attributes' => null
            ]
        ])

        <x-ui.page-header title="Product Attributes" description="Manage product attributes and their values.">
            <x-ui.search-box />
            <a href="{{ route('product_attributes.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Add New Attribute</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            @php(
                $headers = ['Attribute Name', 'Position', 'Values', 'Actions']
            )
            <x-ui.table :headers="$headers">
                @forelse ($attributes as $attribute)
                    <tr class="border-b border-gray-200 text-sm hover:bg-amber-50">
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $attribute->name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $attribute->position }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm">
                            <div class="flex flex-wrap gap-2">
                                @forelse ($attribute->values as $value)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $value->value }}
                                        <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 edit-value-btn custom-tooltip-trigger" data-attribute-id="{{ $attribute->id }}" data-value-id="{{ $value->id }}" data-value-name="{{ $value->value }}" data-tooltip="Edit Value">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 delete-value-btn custom-tooltip-trigger" data-attribute-id="{{ $attribute->id }}" data-value-id="{{ $value->id }}" data-tooltip="Delete Value">
                                            <span class="sr-only">Remove value</span>
                                            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                            </svg>
                                        </button>
                                    </span>
                                @empty
                                    <span class="text-gray-500 text-sm">No values yet.</span>
                                @endforelse
                                <button type="button" class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 add-value-btn custom-tooltip-trigger" data-attribute-id="{{ $attribute->id }}" data-tooltip="Add New Value">
                                    <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Add Value
                                </button>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 text-sm text-right">
                            <x-ui.table-actions :item="$attribute" baseRoute="product_attributes" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-sm text-center">
                            No product attributes found.
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>
            <div class="mt-4 flex justify-end">
                {{ $attributes->links('components.ui.custom-pagination') }}
            </div>
        </x-ui.content-card>
    </div>

    <!-- Modals for Add/Edit Value -->
    <div id="addValueModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Add New Value</h3>
            <form id="addValueForm" method="POST">
                @csrf
                <input type="hidden" name="attribute_id" id="addValueAttributeId">
                <div class="mb-4">
                    <label for="valueName" class="block text-sm font-medium text-gray-700">Value Name</label>
                    <input type="text" name="value" id="valueName" class="mt-2 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400" onclick="document.getElementById('addValueModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Value</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editValueModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Edit Value</h3>
            <form id="editValueForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="attribute_id" id="editValueAttributeId">
                <input type="hidden" name="value_id" id="editValueValueId">
                <div class="mb-4">
                    <label for="editValueName" class="block text-sm font-medium text-gray-700">Value Name</label>
                    <input type="text" name="value" id="editValueName" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400" onclick="document.getElementById('editValueModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Value</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
{{-- The confirmDelete function is now handled within table-actions.blade.php --}}
@endpush