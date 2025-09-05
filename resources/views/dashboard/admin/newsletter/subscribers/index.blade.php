@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')

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
        @include('components.ui.breadcrumbs', ['links' => ['Newsletter Subscribers' => null]])

        <x-ui.page-header title="Newsletter Subscribers" description="Manage your newsletter subscribers.">
            <x-ui.search-box />
            <a href="{{ route('admin.newsletter.send') }}" class="ml-4 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Send Newsletter</span>
            </a>
        </x-ui.page-header>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @include('components.ui.table-filter')

        <x-ui.content-card>
            <div id="subscribers-table-container">
                <!-- Subscribers table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="mt-4 flex justify-end">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </x-ui.content-card>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-10 w-10 mx-auto">
            <p class="mt-2 text-gray-600">Loading subscribers...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const subscribersTableContainer = document.getElementById('subscribers-table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const searchInput = document.querySelector('input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Function to fetch and render subscriber data
            async function fetchSubscribersData(page = 1) {
                loadingSpinner.style.display = 'block';
                subscribersTableContainer.innerHTML = ''; // Clear previous content
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
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    renderSubscribersTable(data.subscribers.data);
                    renderPagination(data.subscribers);

                } catch (error) {
                    console.error('Error fetching subscriber data:', error);
                    subscribersTableContainer.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load subscribers. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render subscribers table
            function renderSubscribersTable(subscribers) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed At</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (subscribers.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="4" class="px-5 py-5 text-sm text-center text-gray-500">
                                No newsletter subscribers found.
                            </td>
                        </tr>
                    `;
                } else {
                    subscribers.forEach(subscriber => {
                        const statusClass = subscriber.is_subscribed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        const statusText = subscriber.is_subscribed ? 'Subscribed' : 'Unsubscribed';
                        const toggleButtonText = subscriber.is_subscribed ? 'Unsubscribe' : 'Subscribe';
                        const toggleButtonClass = subscriber.is_subscribed ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900';

                        tableHtml += `
                            <tr class="border-b border-gray-200 text-sm hover:bg-amber-50" data-id="${subscriber.id}">
                                <td class="px-5 py-5 whitespace-nowrap">${subscriber.email}</td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}" data-status-text="${statusText}">
                                        ${statusText}
                                    </span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">${new Date(subscriber.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                                <td class="px-5 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/admin/newsletter/subscribers/${subscriber.id}/edit" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <button type="button" class="toggle-status-btn ${toggleButtonClass}" data-subscriber-id="${subscriber.id}" data-is-subscribed="${subscriber.is_subscribed}" title="Toggle Status">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                        </button>
                                        <span class="text-gray-300">|</span>
                                        <form action="/admin/newsletter/subscribers/${subscriber.id}" method="POST" class="inline-block delete-form" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
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
                subscribersTableContainer.innerHTML = tableHtml;
            }

            // Function to render pagination
            function renderPagination(paginationData) {
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
                    paginationContainer.innerHTML = paginationHtml;

                    // Add event listeners for pagination links
                    paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetchSubscribersData(this.dataset.page);
                        });
                    });
                } else {
                    paginationContainer.innerHTML = ''; // Clear pagination if only one page
                }
            }

            // Initial fetch of data when the page loads
            fetchSubscribersData();

            // Search input event listener
            if (searchInput) {
                searchInput.addEventListener('input', () => fetchSubscribersData());
            }

            // Toggle Status button event listener (using event delegation)
            subscribersTableContainer.addEventListener('click', async function(event) {
                const toggleBtn = event.target.closest('.toggle-status-btn');
                if (toggleBtn) {
                    const subscriberId = toggleBtn.dataset.subscriberId;
                    const isSubscribed = toggleBtn.dataset.isSubscribed === 'true';

                    Swal.fire({
                        title: 'Confirm Status Change?',
                        text: `Are you sure you want to ${isSubscribed ? 'unsubscribe' : 'subscribe'} this user?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!',
                        focusCancel: true,
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/admin/newsletter/subscribers/${subscriberId}/toggle-status`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify({ _method: 'PATCH' })
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire('Updated!', data.message, 'success');
                                    // Re-fetch data to update the table and apply highlight
                                    fetchSubscribersData().then(() => {
                                        const rowToHighlight = subscribersTableContainer.querySelector(`tr[data-id="${data.subscriber_id}"]`);
                                        if (rowToHighlight) {
                                            rowToHighlight.classList.add('highlight-row');
                                            setTimeout(() => {
                                                rowToHighlight.classList.remove('highlight-row');
                                            }, 5000);
                                        }
                                    });
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to update status.', 'error');
                                }
                            } catch (error) {
                                console.error('Error toggling status:', error);
                                Swal.fire('Error!', 'An error occurred while updating status.', 'error');
                            }
                        }
                    });
                }
            });

            // Highlight subscriber row if redirected from edit
            const highlightSubscriberId = {{ session('highlight_subscriber_id') ?? 'null' }};
            if (highlightSubscriberId) {
                const checkTableInterval = setInterval(() => {
                    const subscriberRow = subscribersTableContainer.querySelector(`tr[data-id="${highlightSubscriberId}"]`);
                    if (subscriberRow) {
                        clearInterval(checkTableInterval);
                        subscriberRow.classList.add('highlight-row');
                        setTimeout(() => {
                            subscriberRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Handle delete form submission with SweetAlert
            subscribersTableContainer.addEventListener('submit', function(event) {
                if (event.target.classList.contains('delete-form')) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                }
            });
        });
    </script>
@endpush
