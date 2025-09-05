@extends('layouts.admin')

@section('title', 'Category Management')

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
    <div class="container mx-auto">
        @include('components.breadcrumbs', [
            'links' => [
                'Categories' => null
            ]
        ])
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Category Management</h1>
                <p class="text-gray-600 mt-1">Organize your product categories hierarchically</p>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="{{ route('categories.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Category
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Category Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">All Categories</h2>
                </div>
                <div id="all-categories-table-body" class="overflow-x-auto">
                    <!-- All Categories table content will be loaded here via AJAX -->
                </div>
                <div id="pagination-all-categories" class="px-6 py-4 border-t border-gray-200">
                    <!-- Pagination for All Categories will be loaded here via AJAX -->
                </div>
                <!-- Spinner for All Categories table -->
                <div id="loading-spinner-all-categories" class="text-center py-8" style="display: none;">
                    <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-10 w-10 mx-auto">
                    <p class="mt-2 text-gray-600">Loading categories...</p>
                </div>
            </div>

            <!-- Category Tree -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Category Hierarchy</h2>
                </div>
                <div id="category-tree-body" class="p-6">
                    <!-- Category tree content will be loaded here via AJAX -->
                </div>
                <!-- Spinner for Category Tree -->
                <div id="loading-spinner-category-tree" class="text-center py-8" style="display: none;">
                    <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-10 w-10 mx-auto">
                    <p class="mt-2 text-gray-600">Loading hierarchy...</p>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allCategoriesTableBody = document.getElementById('all-categories-table-body');
            const paginationAllCategories = document.getElementById('pagination-all-categories');
            const loadingSpinnerAllCategories = document.getElementById('loading-spinner-all-categories');
            const categoryTreeBody = document.getElementById('category-tree-body');
            const loadingSpinnerCategoryTree = document.getElementById('loading-spinner-category-tree');
            const searchInput = document.querySelector('x-ui-search-box input[name="search"]');
            const currentUrl = new URL(window.location.href);

            // Function to fetch and render all categories data (paginated list)
            async function fetchAllCategoriesData(page = 1) {
                loadingSpinnerAllCategories.style.display = 'block';
                allCategoriesTableBody.innerHTML = ''; // Clear previous content
                paginationAllCategories.innerHTML = ''; // Clear previous pagination

                const params = new URLSearchParams();
                if (searchInput && searchInput.value) {
                    params.append('search', searchInput.value);
                }
                params.append('page', page);
                params.append('data_type', 'list'); // Request list data

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

                    renderAllCategoriesTable(data.categories.data);
                    renderPagination(data.categories, paginationAllCategories, fetchAllCategoriesData);

                } catch (error) {
                    console.error('Error fetching all categories data:', error);
                    allCategoriesTableBody.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load categories. Please try again.</p>';
                } finally {
                    loadingSpinnerAllCategories.style.display = 'none';
                }
            }

            // Function to fetch and render category tree data
            async function fetchCategoryTreeData() {
                loadingSpinnerCategoryTree.style.display = 'block';
                categoryTreeBody.innerHTML = ''; // Clear previous content

                const params = new URLSearchParams();
                params.append('data_type', 'tree'); // Request tree data

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

                    renderCategoryTree(data.treeHtml);

                } catch (error) {
                    console.error('Error fetching category tree data:', error);
                    categoryTreeBody.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load category tree. Please try again.</p>';
                } finally {
                    loadingSpinnerCategoryTree.style.display = 'none';
                }
            }

            // Function to render all categories table
            function renderAllCategoriesTable(categories) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;

                if (categories.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No categories found.
                            </td>
                        </tr>
                    `;
                } else {
                    categories.forEach(category => {
                        const statusClass = category.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                        const parentDisplay = category.parent ? `<div class="text-xs text-gray-500">Child of ${category.parent.name}</div>` : '';

                        tableHtml += `
                            <tr class="hover:bg-gray-50" data-id="${category.id}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center">
                                            ${category.image ? `<img class="h-10 w-10 rounded-md object-cover" src="${category.image}" alt="${category.name}">` : `<svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>`}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">${category.name}</div>
                                            ${parentDisplay}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${category.slug}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                        ${category.status.charAt(0).toUpperCase() + category.status.slice(1)}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/categories/${category.id}" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="View Category">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="/categories/${category.id}/edit" class="text-indigo-600 hover:text-indigo-900 custom-tooltip-trigger" data-tooltip="Edit Category">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="/categories/${category.id}" method="POST" class="inline delete-form" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Category">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                `;
                allCategoriesTableBody.innerHTML = tableHtml;
            }

            // Function to render category tree
            function renderCategoryTree(treeHtml) {
                categoryTreeBody.innerHTML = treeHtml;
                // Re-initialize Alpine.js components if any are present in the new HTML
                if (window.Alpine) {
                    window.Alpine.initTree(categoryTreeBody);
                }
            }

            // Function to render pagination
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
            fetchAllCategoriesData();
            fetchCategoryTreeData();

            // Search input event listener
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    fetchAllCategoriesData();
                    // Optionally, re-fetch tree data if search affects it
                    // fetchCategoryTreeData();
                });
            }

            // Handle delete form submission with SweetAlert and AJAX
            allCategoriesTableBody.addEventListener('submit', async function(event) {
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
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire('Deleted!', data.message, 'success');
                                    // Re-fetch both tables to ensure consistency
                                    fetchAllCategoriesData();
                                    fetchCategoryTreeData();
                                } else {
                                    Swal.fire('Error!', data.message || 'Failed to delete category.', 'error');
                                }
                            } catch (error) {
                                console.error('Error deleting category:', error);
                                Swal.fire('Error!', 'An error occurred while deleting the category.', 'error');
                            }
                        }
                    });
                }
            });

            // Highlight category row if redirected from edit
            const highlightCategoryId = {{ session('highlight_category_id') ?? 'null' }};
            if (highlightCategoryId) {
                const checkTableInterval = setInterval(() => {
                    const categoryRow = allCategoriesTableBody.querySelector(`tr[data-id="${highlightCategoryId}"]`);
                    if (categoryRow) {
                        clearInterval(checkTableInterval);
                        categoryRow.classList.add('highlight-row');
                        setTimeout(() => {
                            categoryRow.classList.remove('highlight-row');
                        }, 5000);
                    }
                }, 100);
            }

            // Tree node toggle functionality (re-initialize for dynamically loaded content)
            // This part needs to be called after new tree HTML is loaded
            // The renderCategoryTree function already includes a call to Alpine.initTree(categoryTreeBody);
            // Ensure Alpine.js is loaded and Alpine.initTree is available.

        });

        // Re-initialize Alpine.js components for dynamically loaded content
        // This function needs to be globally accessible or attached to Alpine.js
        if (window.Alpine) {
            window.Alpine.initTree = (el) => {
                el.querySelectorAll('[x-data]').forEach(node => {
                    if (!node.__alpine_initialized) {
                        window.Alpine.initTree(node);
                        node.__alpine_initialized = true;
                    }
                });
            };
        }

        // Tree node toggle functionality (original from Blade, adapted for dynamic content)
        document.addEventListener('alpine:init', () => {
            Alpine.data('treeNode', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>
@endpush
