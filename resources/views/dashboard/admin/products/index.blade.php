@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', [
            'links' => [
                'Products' => route('products.index'),
            ],
            'title' => 'Product Management'
        ])

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Products</h1>
            @can('create', App\Models\Product::class)
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Product
                </a>
            @endcan
        </div>

        <!-- Filters and Search -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Search by name or SKU..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div class="flex gap-4">
                    <select id="statusFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                        <option value="trashed">Trashed</option>
                    </select>
                    <select id="stockFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">All Stock Statuses</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="backorder">Backorder</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Active Products Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="p-4 bg-white border-b">
                <h2 class="text-lg font-medium text-gray-700">Product List</h2>
            </div>
            <div class="overflow-x-auto">
                <table id="productTable" class="w-full border-collapse">
                    <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="name">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Name</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="sku">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> SKU</span>
                        </th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 cursor-pointer" data-column="price">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Price</span>
                        </th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 cursor-pointer" data-column="sale_price">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Sale Price</span>
                        </th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 cursor-pointer" data-column="stock_quantity">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Stock</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="stock_status">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Stock Status</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="status">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Status</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="category">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Category</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer" data-column="brand">
                            <span class="inline-flex items-center"><i class="fas fa-sort mr-1"></i> Brand</span>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="{{ $product->trashed() ? 'bg-red-50' : 'hover:bg-gray-50' }} transition">
                            <td class="px-4 py-3 text-sm text-gray-900 max-w-48 truncate">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $product->sku }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ format_taka($product->price ?? 0) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ format_taka($product->sale_price ?? 0) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ $product->stock_quantity }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' :
                                       ($product->stock_status === 'out_of_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $product->trashed() ? 'bg-red-100 text-red-800' :
                                       ($product->status === 'published' ? 'bg-green-100 text-green-800' :
                                       ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ $product->trashed() ? 'Trashed' : ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @if ($product->categories->isNotEmpty())
                                    <ul class="list-disc list-inside">
                                        @foreach ($product->categories as $category)
                                            <li class="truncate">{{ $category->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex space-x-3">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-800" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @can('update', $product)
                                        @if (!$product->trashed())
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('restore', $product)
                                        @if ($product->trashed())
                                            <form action="{{ route('products.restore', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this product?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Restore">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-13.357-2M9 9H4.582A8.001 8.001 0 0120.581 11m-8.581 2H20"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Delete confirmation with SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        focusCancel: true,
                        reverseButtons: true,
                        backdrop: `
                            rgba(0,0,123,0.4)
                            url("https://sweetalert2.github.io/images/nyan-cat.gif")
                            left top
                            no-repeat
                        `,
                        customClass: {
                            confirmButton: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 mr-3'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Sorting Functionality
            const table = document.getElementById('productTable');
            const headers = table.querySelectorAll('th[data-column]');
            let currentSortColumn = null;
            let isAscending = true;

            console.log('Found headers:', headers.length); // Debug: Verify headers

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.dataset.column;
                    const icon = header.querySelector('i');

                    console.log('Clicked column:', column); // Debug: Confirm click

                    if (currentSortColumn === column) {
                        isAscending = !isAscending;
                    } else {
                        isAscending = true;
                        currentSortColumn = column;
                    }

                    headers.forEach(h => {
                        const i = h.querySelector('i');
                        if (i) {
                            i.classList.remove('fa-sort-up', 'fa-sort-down');
                            i.classList.add('fa-sort');
                        }
                    });

                    if (icon) {
                        icon.classList.remove('fa-sort');
                        icon.classList.add(isAscending ? 'fa-sort-up' : 'fa-sort-down');
                    }

                    const rows = Array.from(table.querySelectorAll('tbody tr'));
                    rows.sort((a, b) => {
                        let aValue, bValue;

                        if (column === 'category') {
                            const aCategories = a.querySelector('td:nth-child(8)').querySelector('ul');
                            const bCategories = b.querySelector('td:nth-child(8)').querySelector('ul');
                            aValue = aCategories ? (aCategories.querySelector('li')?.textContent.trim().toLowerCase() || 'n/a') : 'n/a';
                            bValue = bCategories ? (bCategories.querySelector('li')?.textContent.trim().toLowerCase() || 'n/a') : 'n/a';
                        } else if (column === 'brand') {
                            aValue = a.querySelector('td:nth-child(9)').textContent.trim().toLowerCase() || 'n/a';
                            bValue = b.querySelector('td:nth-child(9)').textContent.trim().toLowerCase() || 'n/a';
                        } else {
                            aValue = a.querySelector(`td:nth-child(${getColumnIndex(column)})`).textContent.trim().toLowerCase();
                            bValue = b.querySelector(`td:nth-child(${getColumnIndex(column)})`).textContent.trim().toLowerCase();
                        }

                        if (aValue === '' && bValue !== '') return isAscending ? 1 : -1;
                        if (bValue === '' && aValue !== '') return isAscending ? -1 : 1;
                        if (aValue === '' && bValue === '') return 0;

                        if (['price', 'sale_price', 'stock_quantity'].includes(column)) {
                            aValue = parseFloat(aValue.replace(/[^0-9.-]+/g, '')) || 0;
                            bValue = parseFloat(bValue.replace(/[^0-9.-]+/g, '')) || 0;
                            return isAscending ? aValue - bValue : bValue - aValue;
                        }

                        if (column === 'status') {
                            const aTrashed = a.classList.contains('bg-red-50');
                            const bTrashed = b.classList.contains('bg-red-50');
                            if (aTrashed && !bTrashed) return isAscending ? 1 : -1;
                            if (!aTrashed && bTrashed) return isAscending ? -1 : 1;
                        }

                        return isAscending
                            ? aValue.localeCompare(bValue, undefined, { numeric: true })
                            : bValue.localeCompare(aValue, undefined, { numeric: true });
                    });

                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    rows.forEach(row => tbody.appendChild(row));

                    filterTable();
                });
            });

            function getColumnIndex(column) {
                const columns = ['name', 'sku', 'price', 'sale_price', 'stock_quantity', 'stock_status', 'status', 'category', 'brand'];
                return columns.indexOf(column) + 1;
            }

            // Combined Search and Filter Functionality
            function filterTable() {
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');
                const stockFilter = document.getElementById('stockFilter');

                if (!searchInput || !statusFilter || !stockFilter) {
                    console.error('Filter elements not found');
                    return;
                }

                const searchValue = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const stockValue = stockFilter.value.toLowerCase();
                const rows = document.querySelectorAll('#productTable tbody tr');

                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const sku = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(7)').textContent.toLowerCase().trim();
                    const stock = row.querySelector('td:nth-child(6)').textContent.toLowerCase().trim();
                    const isTrashed = row.classList.contains('bg-red-50');

                    const matchesSearch = !searchValue || name.includes(searchValue) || sku.includes(searchValue);
                    const matchesStatus = !statusValue ||
                        (statusValue === 'trashed' && isTrashed) ||
                        (!isTrashed && status === statusValue);
                    const matchesStock = !stockValue || stock === stockValue;

                    row.style.display = matchesSearch && matchesStatus && matchesStock ? '' : 'none';
                });
            }

            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const stockFilter = document.getElementById('stockFilter');

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (statusFilter) statusFilter.addEventListener('change', filterTable);
            if (stockFilter) stockFilter.addEventListener('change', filterTable);

            filterTable();
        });
    </script>
@endpush
