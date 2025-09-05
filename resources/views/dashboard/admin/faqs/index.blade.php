@extends('layouts.admin')

@section('title', 'FAQs Management')

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
    
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FAQs Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your frequently asked questions</p>
            </div>

            <div class="flex flex-wrap gap-3 items-center">
                <x-ui.search-box />
                <!-- Add FAQ Button -->
                <a href="{{ route('faqs.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add FAQ
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- FAQs Table Container -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div id="faqs-table-container">
                <!-- FAQs table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="mt-4 flex">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </div>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-10 w-10 mx-auto">
            <p class="mt-2 text-gray-600">Loading FAQs...</p>
        </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const faqsTableContainer = document.getElementById('faqs-table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            const searchInput = document.querySelector('input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Function to fetch and render FAQ data
            async function fetchFaqsData(page = 1) {
                loadingSpinner.style.display = 'block';
                faqsTableContainer.innerHTML = ''; // Clear previous content
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

                    renderFaqsTable(data.faqs.data);
                    renderPagination(data.faqs, paginationContainer, fetchFaqsData);

                } catch (error) {
                    console.error('Error fetching FAQ data:', error);
                    faqsTableContainer.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load FAQs. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render FAQs table
            function renderFaqsTable(faqs) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (faqs.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No FAQs found</h3>
                                    <a href="{{ route('faqs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Add New FAQ
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    faqs.forEach(faq => {
                        tableHtml += `
                            <tr class="hover:bg-gray-50" data-id="${faq.id}">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        ${faq.question}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        ${faq.answer.substring(0, 100)}${faq.answer.length > 100 ? '...' : ''}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/faqs/${faq.id}/edit" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit FAQ">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="/faqs/${faq.id}" method="POST" class="delete-form inline-block" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete FAQ">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
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
                faqsTableContainer.innerHTML = tableHtml;
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
            fetchFaqsData();

            // Search input event listener with debounce
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchFaqsData();
                    }, 300); // Debounce for 300ms
                });
            }

            // Highlight FAQ row if redirected from edit/create
            const highlightFaqId = {{ session('highlight_faq_id') ?? 'null' }};
            if (highlightFaqId) {
                const checkTableInterval = setInterval(() => {
                    const faqRow = faqsTableContainer.querySelector(`tr[data-id="${highlightFaqId}"]`);
                    if (faqRow) {
                        clearInterval(checkTableInterval);
                        faqRow.classList.add('highlight-row');
                        setTimeout(() => {
                            faqRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Handle delete form submission with SweetAlert and AJAX
            faqsTableContainer.addEventListener('submit', async function(event) {
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
                                    // Re-fetch data to update the table
                                    fetchFaqsData();
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to delete FAQ.', 'error');
                                }
                            } catch (error) {
                                console.error('Error deleting FAQ:', error);
                                Swal.fire('Error!', error.message || 'An error occurred while deleting the FAQ.', 'error');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush