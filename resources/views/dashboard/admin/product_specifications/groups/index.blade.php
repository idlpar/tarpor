@extends('layouts.admin')

@section('title', 'Manage Product Specification Groups')

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
            <div id="groups-table-container">
                <!-- Groups table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="mt-4">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </x-ui.content-card>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-24 w-24 mx-auto">
            <p class="mt-2 text-gray-600">Loading groups...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const groupsTableContainer = document.getElementById('groups-table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const searchInput = document.querySelector('input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Function to fetch and render group data
            async function fetchGroupsData(page = 1) {
                loadingSpinner.style.display = 'block';
                groupsTableContainer.innerHTML = ''; // Clear previous content
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

                    renderGroupsTable(data.groups.data);
                    renderPagination(data.groups, paginationContainer, fetchGroupsData);

                } catch (error) {
                    console.error('Error fetching group data:', error);
                    groupsTableContainer.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load groups. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render groups table
            function renderGroupsTable(groups) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (groups.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="4" class="px-5 py-5 text-sm text-center text-gray-500">
                                No product specification groups found.
                            </td>
                        </tr>
                    `;
                } else {
                    groups.forEach(group => {
                        const statusClass = group.deleted_at ? 'bg-red-200 text-red-900' : 'bg-green-200 text-green-900';
                        const statusText = group.deleted_at ? 'Trashed' : 'Active';
                        const trashedBadge = group.deleted_at ? '<span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 ml-2">Trashed</span>' : '';

                        tableHtml += `
                            <tr class="border-b border-gray-200 text-sm hover:bg-amber-50" data-id="${group.id}">
                                <td class="px-5 py-5 whitespace-nowrap">${group.name}</td>
                                <td class="px-5 py-5 whitespace-nowrap">${group.description ?? 'N/A'}</td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="relative inline-block px-3 py-1 font-semibold ${statusClass} leading-tight">
                                        <span aria-hidden="true" class="absolute inset-0 ${statusClass.replace('text-', 'bg-').replace('-900', '-200')} opacity-50 rounded-full"></span>
                                        <span class="relative">${statusText}</span>
                                    </span>
                                    ${trashedBadge}
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/product-specifications/groups/${group.id}/edit" class="text-indigo-600 hover:text-indigo-900 custom-tooltip-trigger" data-tooltip="Edit Group">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        ${group.deleted_at ? `
                                            <form action="/product-specifications/groups/${group.id}/restore" method="POST" class="inline restore-form" onsubmit="return false;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900 custom-tooltip-trigger" data-tooltip="Restore Group">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                </button>
                                            </form>
                                            <span class="text-gray-300">|</span>
                                            <form action="/product-specifications/groups/${group.id}/force-delete" method="POST" class="inline force-delete-form" onsubmit="return false;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Force Delete Group">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        ` : `
                                            <form action="/product-specifications/groups/${group.id}" method="POST" class="inline delete-form" onsubmit="return false;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Group">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        `}
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
                groupsTableContainer.innerHTML = tableHtml;
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
            fetchGroupsData();

            // Search input event listener with debounce
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchGroupsData();
                    }, 300); // Debounce for 300ms
                });
            }

            // Highlight group row if redirected from edit/create
            const highlightGroupId = {{ session('highlight_group_id') ?? 'null' }};
            if (highlightGroupId) {
                const checkTableInterval = setInterval(() => {
                    const groupRow = groupsTableContainer.querySelector(`tr[data-id="${highlightGroupId}"]`);
                    if (groupRow) {
                        clearInterval(checkTableInterval);
                        groupRow.classList.add('highlight-row');
                        setTimeout(() => {
                            groupRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Handle delete, restore, force delete form submissions with SweetAlert and AJAX
            groupsTableContainer.addEventListener('submit', async function(event) {
                if (event.target.classList.contains('delete-form') ||
                    event.target.classList.contains('restore-form') ||
                    event.target.classList.contains('force-delete-form')) {

                    event.preventDefault();
                    const form = event.target;
                    const actionUrl = form.action;
                    const method = form.querySelector('input[name="_method"]').value;

                    let title = 'Are you sure?';
                    let text = "You won't be able to revert this!";
                    let confirmButtonText = 'Yes, proceed!';
                    let icon = 'warning';

                    if (form.classList.contains('restore-form')) {
                        title = 'Restore Group?';
                        text = 'This will restore the group.';
                        confirmButtonText = 'Yes, restore it!';
                        icon = 'info';
                    } else if (form.classList.contains('force-delete-form')) {
                        title = 'Permanently Delete Group?';
                        text = 'This action cannot be undone. The group will be permanently deleted.';
                        confirmButtonText = 'Yes, delete permanently!';
                        icon = 'error';
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText,
                        focusCancel: true
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(actionUrl, {
                                    method: 'POST', // Always POST for Laravel forms with _method
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify({ _method: method })
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire('Success!', data.message, 'success');
                                    // Re-fetch data to update the table
                                    fetchGroupsData();
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to perform action.', 'error');
                                }
                            } catch (error) {
                                console.error(`Error ${method}ing group:`, error);
                                Swal.fire('Error!', error.message || 'An error occurred while performing the action.', 'error');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
