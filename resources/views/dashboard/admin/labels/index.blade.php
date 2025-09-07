@extends('layouts.admin')

@section('title', 'Product Labels')

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
                'Labels' => null
            ]
        ])

        <x-ui.page-header title="Product Labels" description="Manage and organize labels for your products.">
            <x-ui.search-box />
            <a href="{{ route('labels.create') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add New Label</span>
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        @include('components.ui.table-filter')

        <x-ui.content-card>
            <div id="labels-table-container">
                <!-- Labels table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="mt-4">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </x-ui.content-card>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-24 w-24 mx-auto">
            <p class="mt-2 text-gray-600">Loading labels...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labelsTableContainer = document.getElementById('labels-table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const searchInput = document.querySelector('input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Function to fetch and render label data
            async function fetchLabelsData(page = 1) {
                loadingSpinner.style.display = 'block';
                labelsTableContainer.innerHTML = ''; // Clear previous content
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

                    renderLabelsTable(data.labels.data);
                    renderPagination(data.labels, paginationContainer, fetchLabelsData);

                } catch (error) {
                    console.error('Error fetching label data:', error);
                    labelsTableContainer.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load labels. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render labels table
            function renderLabelsTable(labels) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (labels.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="5" class="px-5 py-5 text-sm text-center text-gray-500">
                                No labels found.
                            </td>
                        </tr>
                    `;
                } else {
                    labels.forEach(label => {
                        const statusClass = label.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

                        tableHtml += `
                            <tr class="border-b border-gray-200 text-sm hover:bg-amber-50" data-id="${label.id}">
                                <td class="px-5 py-5 whitespace-nowrap">${label.name}</td>
                                <td class="px-5 py-5 whitespace-nowrap">${label.slug}</td>
                                <td class="px-5 py-5 whitespace-nowrap">${label.description ? label.description.substring(0, 50) + (label.description.length > 50 ? '...' : '') : ''}</td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                        ${label.status.charAt(0).toUpperCase() + label.status.slice(1)}
                                    </span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/labels/${label.id}/edit" class="text-indigo-600 hover:text-indigo-900 custom-tooltip-trigger" data-tooltip="Edit Label">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="/labels/${label.id}" method="POST" class="inline delete-form" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Label">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                labelsTableContainer.innerHTML = tableHtml;
            }

            // Function to render pagination (re-used from other pages)
            function renderPagination(paginationData, container, fetchDataFunction) {
                container.innerHTML = ''; // Explicitly clear container before rendering
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
            fetchLabelsData();

            // Search input event listener with debounce
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchLabelsData();
                    }, 300); // Debounce for 300ms
                });
            }

            // Highlight label row if redirected from edit/create
            const highlightLabelId = {{ session('highlight_label_id') ?? 'null' }};
            if (highlightLabelId) {
                const checkTableInterval = setInterval(() => {
                    const labelRow = labelsTableContainer.querySelector(`tr[data-id="${highlightLabelId}"]`);
                    if (labelRow) {
                        clearInterval(checkTableInterval);
                        labelRow.classList.add('highlight-row');
                        setTimeout(() => {
                            labelRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Handle delete form submission with SweetAlert and AJAX
            labelsTableContainer.addEventListener('submit', async function(event) {
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
                                    // Remove the row from the DOM
                                    const rowToRemove = form.closest('tr');
                                    if (rowToRemove) {
                                        rowToRemove.remove();
                                    }
                                    // Re-fetch data to update pagination and potentially fill empty space
                                    fetchLabelsData();
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to delete label.', 'error');
                                }
                            } catch (error) {
                                console.error('Error deleting label:', error);
                                Swal.fire('Error!', error.message || 'An error occurred while deleting the label.', 'error');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
