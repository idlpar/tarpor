@extends('layouts.admin')

@section('title', 'Manage Product Attributes')

@push('styles')
    <style>
        .highlight-row {
            animation: highlight 5s ease-out forwards;
        }

        @keyframes highlight {
            0% { background-color: #e6ffed; } /* Light green */
            100% { background-color: transparent; } /* Fade to transparent */
        }
    </style>
@endpush

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
            <div id="attributes-table-container">
                <!-- Attributes table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="mt-4">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </x-ui.content-card>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-24 w-24 mx-auto">
            <p class="mt-2 text-gray-600">Loading attributes...</p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const attributesTableContainer = document.getElementById('attributes-table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const searchInput = document.querySelector('input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Modals and Forms for Attribute Values
            const addValueModal = document.getElementById('addValueModal');
            const addValueForm = document.getElementById('addValueForm');
            const addValueAttributeIdInput = document.getElementById('addValueAttributeId');
            const valueNameInput = document.getElementById('valueName');

            const editValueModal = document.getElementById('editValueModal');
            const editValueForm = document.getElementById('editValueForm');
            const editValueAttributeIdInput = document.getElementById('editValueAttributeId');
            const editValueValueIdInput = document.getElementById('editValueValueId');
            const editValueNameInput = document.getElementById('editValueName');

            // Function to fetch and render attribute data
            async function fetchAttributesData(page = 1) {
                loadingSpinner.style.display = 'block';
                attributesTableContainer.innerHTML = ''; // Clear previous content
                paginationContainer.innerHTML = ''; // Clear previous pagination

                const params = new URLSearchParams();
                if (searchInput && searchInput.value) {
                    params.append('search', searchInput.value);
                }
                params.append('page', page);

                try {
                    const response = await fetch(`${currentUrl.origin}${currentUrl.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    renderAttributesTable(data.attributes.data);
                    renderPagination(data.attributes, paginationContainer, fetchAttributesData);

                } catch (error) {
                    console.error('Error fetching attribute data:', error);
                    attributesTableContainer.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load attributes. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render attributes table
            function renderAttributesTable(attributes) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attribute Name</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Values</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (attributes.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="4" class="px-5 py-5 text-sm text-center text-gray-500">
                                No product attributes found.
                            </td>
                        </tr>
                    `;
                } else {
                    attributes.forEach(attribute => {
                        const valuesHtml = attribute.values.map(value => `
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                ${value.value}
                                <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 edit-value-btn custom-tooltip-trigger" data-attribute-id="${attribute.id}" data-value-id="${value.id}" data-value-name="${value.value}" data-tooltip="Edit Value">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-green-400 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-200 focus:text-green-500 delete-value-btn custom-tooltip-trigger" data-attribute-id="${attribute.id}" data-value-id="${value.id}" data-tooltip="Delete Value">
                                    <span class="sr-only">Remove value</span>
                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                    </svg>
                                </button>
                            </span>
                        `).join('');

                        tableHtml += `
                            <tr class="border-b border-gray-200 text-sm hover:bg-amber-50" data-id="${attribute.id}">
                                <td class="px-5 py-5 whitespace-nowrap">${attribute.name}</td>
                                <td class="px-5 py-5 whitespace-nowrap">${attribute.position ?? 'N/A'}</td>
                                <td class="px-5 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        ${valuesHtml}
                                        <button type="button" class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 add-value-btn custom-tooltip-trigger" data-attribute-id="${attribute.id}" data-tooltip="Add New Value">
                                            <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Add Value
                                        </button>
                                    </div>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/product-attributes/${attribute.id}/edit" class="text-indigo-600 hover:text-indigo-900 custom-tooltip-trigger" data-tooltip="Edit Attribute">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="/product-attributes/${attribute.id}" method="POST" class="inline delete-form" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Attribute">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
                attributesTableContainer.innerHTML = tableHtml;
            }

            // Function to render pagination (re-used from other pages)
            function renderPagination(paginationData, container, fetchDataFunction) {
                if (paginationData.last_page > 1) {
                    let paginationHtml = `
                        <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 sm:px-6">
                            <!-- Pagination Info -->
                            <div class="mb-4 sm:mb-0">
                                <p class="text-sm text-gray-600 font-medium">
                                    Showing <span class="text-[var(--primary)]">${paginationData.from}</span>
                                    to <span class="text-[var(--primary)]">${paginationData.to}</span>
                                    of <span class="text-[var(--primary)]">${paginationData.total}</span> results
                                </p>
                            </div>

                            <!-- Pagination Links -->
                            <nav class="flex items-center space-x-1">
                    `;

                    paginationData.links.forEach(link => {
                        if (link.url) {
                            const pageNum = new URL(link.url).searchParams.get('page') || 1;
                            paginationHtml += `
                                <a href="#" data-page="${pageNum}" class="${link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md">
                                    ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                                </a>
                            `;
                        } else {
                            paginationHtml += `
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 cursor-default rounded-md disabled-pagination-link">
                                    ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                                </span>
                            `;
                        }
                    });

                    paginationHtml += `
                                </nav>
                            </div>
                        </div>
                    `;
                    container.innerHTML = paginationHtml;

                    // Add event listeners for pagination links
                    container.querySelectorAll('a[data-page]').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetchDataFunction(this.dataset.page);
                        });
                    });
                } else {
                    container.innerHTML = ''; // Clear pagination if only one page
                }
            }

            // Initial fetch of data when the page loads
            fetchAttributesData();

            // Search input event listener with debounce
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchAttributesData();
                    }, 300); // Debounce for 300ms
                });
            }

            // Highlight attribute row if redirected from edit/create
            const highlightAttributeId = {{ session('highlight_attribute_id') ?? 'null' }};
            if (highlightAttributeId) {
                const checkTableInterval = setInterval(() => {
                    const attributeRow = attributesTableContainer.querySelector(`tr[data-id="${highlightAttributeId}"]`);
                    if (attributeRow) {
                        clearInterval(checkTableInterval);
                        attributeRow.classList.add('highlight-row');
                        setTimeout(() => {
                            attributeRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Handle attribute delete form submission with SweetAlert and AJAX
            attributesTableContainer.addEventListener('submit', async function(event) {
                if (event.target.classList.contains('delete-form')) {
                    event.preventDefault();
                    const form = event.target;
                    const actionUrl = form.action;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        focusCancel: true
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(actionUrl, {
                                    method: 'POST', // Laravel uses POST for DELETE with _method field
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify({ _method: 'DELETE' })
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    fetchAttributesData(); // Re-fetch data to update the table
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to delete attribute.', 'error');
                                }
                            } catch (error) {
                                console.error('Error deleting attribute:', error);
                                Swal.fire('Error!', error.message || 'An error occurred while deleting the attribute.', 'error');
                            }
                        }
                    });
                }
            });

            // Handle Add Value form submission
            addValueForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const attributeId = addValueAttributeIdInput.value;
                const valueName = valueNameInput.value;

                try {
                    const response = await fetch(`/product-attributes/${attributeId}/values`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ value: valueName })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Added!', data.message, 'success');
                        addValueModal.classList.add('hidden');
                        addValueForm.reset();
                        fetchAttributesData(); // Re-fetch data to update the table
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to add value.', 'error');
                    }
                } catch (error) {
                    console.error('Error adding value:', error);
                    Swal.fire('Error!', error.message || 'An error occurred while adding the value.', 'error');
                }
            });

            // Handle Edit Value form submission
            editValueForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const attributeId = editValueAttributeIdInput.value;
                const valueId = editValueValueIdInput.value;
                const valueName = editValueNameInput.value;

                try {
                    const response = await fetch(`/product-attributes/${attributeId}/values/${valueId}`, {
                        method: 'POST', // Use POST for PUT with _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ value: valueName, _method: 'PUT' })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire('Updated!', data.message, 'success');
                        editValueModal.classList.add('hidden');
                        fetchAttributesData(); // Re-fetch data to update the table
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to update value.', 'error');
                    }
                } catch (error) {
                    console.error('Error updating value:', error);
                    Swal.fire('Error!', error.message || 'An error occurred while updating the value.', 'error');
                }
            });

            // Handle Delete Value button click (using event delegation)
            attributesTableContainer.addEventListener('click', async function(event) {
                const deleteValueBtn = event.target.closest('.delete-value-btn');
                if (deleteValueBtn) {
                    const attributeId = deleteValueBtn.dataset.attributeId;
                    const valueId = deleteValueBtn.dataset.valueId;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        focusCancel: true
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/product-attributes/${attributeId}/values/${valueId}`, {
                                    method: 'POST', // Use POST for DELETE with _method
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify({ _method: 'DELETE' })
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    fetchAttributesData(); // Re-fetch data to update the table
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to delete value.', 'error');
                                }
                            } catch (error) {
                                console.error('Error deleting value:', error);
                                Swal.fire('Error!', error.message || 'An error occurred while deleting the value.', 'error');
                            }
                        }
                    });
                }
            });

            // Handle Edit Value button click (using event delegation)
            attributesTableContainer.addEventListener('click', function(event) {
                const editValueBtn = event.target.closest('.edit-value-btn');
                if (editValueBtn) {
                    const attributeId = editValueBtn.dataset.attributeId;
                    const valueId = editValueBtn.dataset.valueId;
                    const valueName = editValueBtn.dataset.valueName;

                    editValueAttributeIdInput.value = attributeId;
                    editValueValueIdInput.value = valueId;
                    editValueNameInput.value = valueName;
                    editValueModal.classList.remove('hidden');
                }
            });

            // Handle Add Value button click (using event delegation)
            attributesTableContainer.addEventListener('click', function(event) {
                const addValueBtn = event.target.closest('.add-value-btn');
                if (addValueBtn) {
                    const attributeId = addValueBtn.dataset.attributeId;
                    addValueAttributeIdInput.value = attributeId;
                    addValueModal.classList.remove('hidden');
                }
            });
        });
    </script>
@endpush
