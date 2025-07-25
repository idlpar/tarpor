@extends('layouts.admin')

@section('title', 'Manage Product Attributes | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="container mx-auto px-4 py-8">
        <!-- Display Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Product Attributes' => route('product_attributes.index'),
            ]
        ])

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manage Product Attributes</h1>
            <a href="{{ route('product_attributes.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Attribute
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Attribute Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Position</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Values</th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($attributes as $attribute)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $attribute->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">{{ $attribute->position }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse ($attribute->values as $value)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $value->value }}
                                                <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 edit-value-btn" data-attribute-id="{{ $attribute->id }}" data-value-id="{{ $value->id }}" data-value-name="{{ $value->value }}" title="Edit Value">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 delete-value-btn" data-attribute-id="{{ $attribute->id }}" data-value-id="{{ $value->id }}" title="Delete Value">
                                                    <span class="sr-only">Remove value</span>
                                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                                    </svg>
                                                </button>
                                            </span>
                                        @empty
                                            <span class="text-gray-500 text-sm">No values yet.</span>
                                        @endforelse
                                        <button type="button" class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 add-value-btn" data-attribute-id="{{ $attribute->id }}">
                                            <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Add Value
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('product_attributes.edit', $attribute->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit Attribute">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('product_attributes.destroy', $attribute->id) }}" method="POST" class="delete-attribute-form inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Attribute">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h2 class="text-xl font-semibold text-gray-700">No Product Attributes Found</h2>
                                        <p class="text-gray-500 mt-1">Get started by adding a new attribute.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $attributes->links() }}
            </div>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delete Attribute Confirmation
        document.querySelectorAll('.delete-attribute-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! All associated values will also be deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Red for delete
                    cancelButtonColor: '#3085d6', // Blue for cancel
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true, // Puts cancel on the left, confirm on the right
                    focusConfirm: false // Ensures cancel is not the default focus
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Add Value Modal Logic
        document.querySelectorAll('.add-value-btn').forEach(button => {
            button.addEventListener('click', function () {
                const attributeId = this.dataset.attributeId;
                document.getElementById('addValueAttributeId').value = attributeId;
                document.getElementById('addValueForm').action = `/product-attributes/${attributeId}/values`;
                document.getElementById('valueName').value = ''; // Clear previous value
                document.getElementById('addValueModal').classList.remove('hidden');
            });
        });

        // Edit Value Modal Logic
        document.querySelectorAll('.edit-value-btn').forEach(button => {
            button.addEventListener('click', function () {
                const attributeId = this.dataset.attributeId;
                const valueId = this.dataset.valueId;
                const valueName = this.dataset.valueName;

                document.getElementById('editValueAttributeId').value = attributeId;
                document.getElementById('editValueValueId').value = valueId;
                document.getElementById('editValueName').value = valueName;
                document.getElementById('editValueForm').action = `/product-attributes/${attributeId}/values/${valueId}`;
                document.getElementById('editValueModal').classList.remove('hidden');
            });
        });

        // Delete Value Confirmation
        document.querySelectorAll('.delete-value-btn').forEach(button => {
            button.addEventListener('click', function () {
                const attributeId = this.dataset.attributeId;
                const valueId = this.dataset.valueId;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Red for delete
                    cancelButtonColor: '#3085d6', // Blue for cancel
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true, // Puts cancel on the left, confirm on the right
                    focusConfirm: false // Ensures cancel is not the default focus
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/product-attributes/${attributeId}/values/${valueId}`;
                        form.style.display = 'none';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Filtering and Sorting (similar to products index, but for attributes)
        const filterToggleButton = document.getElementById('filterToggleButton');
        const filterSection = document.getElementById('filterSection');
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const clearFiltersButton = document.getElementById('clearFiltersButton');

        if (filterToggleButton && filterSection) {
            filterToggleButton.addEventListener('click', () => {
                filterSection.classList.toggle('hidden');
            });
        }

        function applyFiltersAndSort() {
            const params = new URLSearchParams();

            if (searchInput && searchInput.value) {
                params.append('search', searchInput.value);
            }
            if (sortSelect && sortSelect.value) {
                params.append('sort_by', sortSelect.value);
            }

            window.location.href = '{{ route('product_attributes.index') }}?' + params.toString();
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    applyFiltersAndSort();
                }
            });
        }
        
        if (sortSelect) {
            sortSelect.addEventListener('change', applyFiltersAndSort);
        }

        if (clearFiltersButton) {
            clearFiltersButton.addEventListener('click', () => {
                if (searchInput) {
                    searchInput.value = '';
                }
                if (sortSelect) {
                    sortSelect.value = 'name_asc'; // Default sort for attributes
                }
                applyFiltersAndSort();
            });
        }

        // Set initial filter and sort values from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (searchInput && urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        if (sortSelect && urlParams.has('sort_by')) {
            sortSelect.value = urlParams.get('sort_by');
        }

        if (filterSection && urlParams.toString()) {
            filterSection.classList.remove('hidden');
        }
    });
</script>
@endpush
