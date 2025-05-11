{{--@extends('layouts.admin')--}}

{{--@section('page-content')--}}
{{--    <div class="max-w-full h-full bg-sky-100 p-4 md:p-8 transition-all duration-300">--}}
{{--        <!-- Breadcrumbs -->--}}
{{--        @include('components.breadcrumbs', [--}}
{{--             'links' => [--}}
{{--                 'Dashboard' => route('admin.dashboard'),--}}
{{--                 'Products' => route('product.index'),--}}
{{--                 'Create Product' => route('product.create'),--}}
{{--             ],--}}
{{--              'title' => "Admin Dashboard"--}}
{{--         ])--}}

{{--        <!-- Product Table -->--}}
{{--        <div class="w-full bg-white shadow-lg rounded-lg overflow-hidden">--}}
{{--            <div class="p-4">--}}
{{--                <!-- Search and Filters -->--}}
{{--                <div class="flex justify-between items-center mb-4">--}}
{{--                    <input type="text" id="searchInput" placeholder="Search products..." class="w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                    <div class="flex space-x-4">--}}
{{--                        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                            <option value="">All Statuses</option>--}}
{{--                            <option value="draft">Draft</option>--}}
{{--                            <option value="published">Published</option>--}}
{{--                            <option value="archived">Archived</option>--}}
{{--                        </select>--}}
{{--                        <select id="stockFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                            <option value="">All Stock Statuses</option>--}}
{{--                            <option value="in_stock">In Stock</option>--}}
{{--                            <option value="out_of_stock">Out of Stock</option>--}}
{{--                            <option value="backorder">Backorder</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Table -->--}}
{{--                <div class="overflow-auto max-w-full">--}}
{{--                    <table id="productTable" class="w-full border-collapse border border-gray-300 divide-y divide-gray-200">--}}
{{--                        <thead class="bg-lime-500 sticky top-0">--}}
{{--                        <tr>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(0)">--}}
{{--                                <span id="nameSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Name--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(1)">--}}
{{--                                <span id="skuSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> SKU--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(2)">--}}
{{--                                <span id="priceSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Price--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(3)">--}}
{{--                                <span id="salePriceSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Sale Price--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(4)">--}}
{{--                                <span id="costPriceSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Cost Price--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(5)">--}}
{{--                                <span id="stockSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Stock--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(6)">--}}
{{--                                <span id="stockStatusSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Stock Status--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(7)">--}}
{{--                                <span id="statusSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Status--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(8)">--}}
{{--                                <span id="categorySortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Category--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(9)">--}}
{{--                                <span id="brandSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Brand--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="hidden px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(10)">--}}
{{--                                <span id="barcodeSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Barcode--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="hidden px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(11)">--}}
{{--                                <span id="weightSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Weight--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="hidden px-4 py-3 text-left text-sm font-medium text-black cursor-pointer" onclick="sortTable(12)">--}}
{{--                                <span id="dimensionsSortIcon" class="mr-1"><i class="fa-solid fa-sort-down"></i></span> Dimensions--}}
{{--                            </th>--}}
{{--                            <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-black">--}}
{{--                                Actions--}}
{{--                            </th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody class="bg-teal-100 divide-y divide-gray-200">--}}
{{--                        @foreach($products as $product)--}}
{{--                            <tr class="hover:bg-gray-50 transition">--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap max-w-48">--}}
{{--                                    <div class="text-sm text-gray-900 truncate">{{ $product->name }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ $product->sku }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ format_taka($product->price) }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ format_taka($product->sale_price) }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ format_taka($product->cost_price) }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ $product->stock_quantity }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <span class="px-2 py-1 text-sm font-semibold rounded-full--}}
{{--                                        {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' :--}}
{{--                                           ($product->stock_status === 'out_of_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">--}}
{{--                                        {{ ucfirst($product->stock_status) }}--}}
{{--                                    </span>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <span class="px-2 py-1 text-sm font-semibold rounded-full--}}
{{--                                        {{ $product->status === 'published' ? 'bg-green-100 text-green-800' :--}}
{{--                                           ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">--}}
{{--                                        {{ ucfirst($product->status) }}--}}
{{--                                    </span>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">--}}
{{--                                        @if ($product->categories->isNotEmpty())--}}
{{--                                            <ul class="list-inside">--}}
{{--                                                @foreach ($product->categories as $category)--}}
{{--                                                    <li>{{ $category->name }}</li>--}}
{{--                                                @endforeach--}}
{{--                                            </ul>--}}
{{--                                        @else--}}
{{--                                            N/A--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="hidden border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ $product->barcode ?? 'N/A' }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="hidden border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="hidden border border-gray-300 px-2 py-1 whitespace-nowrap">--}}
{{--                                    <div class="text-sm text-gray-900">--}}
{{--                                        {{ $product->length ? $product->length . 'x' . $product->width . 'x' . $product->height . ' cm' : 'N/A' }}--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td class="border border-gray-300 px-2 py-1 whitespace-nowrap text-sm font-medium">--}}
{{--                                    <div class="flex space-x-4">--}}
{{--                                        <a href="{{ route('product.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700">--}}
{{--                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>--}}
{{--                                            </svg>--}}
{{--                                        </a>--}}
{{--                                        <a href="{{ route('product.view', $product->slug) }}" class="text-green-500 hover:text-green-700">--}}
{{--                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>--}}
{{--                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>--}}
{{--                                            </svg>--}}
{{--                                        </a>--}}
{{--                                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit" class="text-red-500 hover:text-red-700">--}}
{{--                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>--}}
{{--                                                </svg>--}}
{{--                                            </button>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--    <!-- JavaScript for Sorting and Filtering -->--}}
{{--    <script>--}}
{{--        // Sorting Functionality--}}
{{--        function sortTable(columnIndex) {--}}
{{--            const table = document.getElementById('productTable');--}}
{{--            const rows = Array.from(table.querySelectorAll('tbody tr'));--}}
{{--            const header = table.querySelector(`th:nth-child(${columnIndex + 1})`);--}}
{{--            const icon = header.querySelector('i');--}}

{{--            // Toggle the sorting direction--}}
{{--            const isAscending = header.classList.toggle('asc');--}}

{{--            // Change the icon based on the sorting direction--}}
{{--            if (isAscending) {--}}
{{--                icon.classList.remove('fa-sort-down');--}}
{{--                icon.classList.add('fa-sort-up');--}}
{{--            } else {--}}
{{--                icon.classList.remove('fa-sort-up');--}}
{{--                icon.classList.add('fa-sort-down');--}}
{{--            }--}}

{{--            // Sort the rows--}}
{{--            rows.sort((a, b) => {--}}
{{--                const aValue = a.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim();--}}
{{--                const bValue = b.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim();--}}
{{--                return isAscending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);--}}
{{--            });--}}

{{--            // Clear and re-append the sorted rows--}}
{{--            table.querySelector('tbody').innerHTML = '';--}}
{{--            rows.forEach(row => table.querySelector('tbody').appendChild(row));--}}
{{--        }--}}

{{--        // Filtering Functionality--}}
{{--        document.getElementById('searchInput').addEventListener('input', function() {--}}
{{--            const searchValue = this.value.toLowerCase();--}}
{{--            const rows = document.querySelectorAll('#productTable tbody tr');--}}

{{--            rows.forEach(row => {--}}
{{--                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();--}}
{{--                const sku = row.querySelector('td:nth-child(2)').textContent.toLowerCase();--}}
{{--                if (name.includes(searchValue) || sku.includes(searchValue)) {--}}
{{--                    row.style.display = '';--}}
{{--                } else {--}}
{{--                    row.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        document.getElementById('statusFilter').addEventListener('change', function() {--}}
{{--            const statusValue = this.value;--}}
{{--            const rows = document.querySelectorAll('#productTable tbody tr');--}}

{{--            rows.forEach(row => {--}}
{{--                const status = row.querySelector('td:nth-child(8)').textContent.toLowerCase().trim();--}}
{{--                if (!statusValue || status === statusValue) {--}}
{{--                    row.style.display = '';--}}
{{--                } else {--}}
{{--                    row.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        document.getElementById('stockFilter').addEventListener('change', function() {--}}
{{--            const stockValue = this.value;--}}
{{--            const rows = document.querySelectorAll('#productTable tbody tr');--}}

{{--            rows.forEach(row => {--}}
{{--                const stock = row.querySelector('td:nth-child(7)').textContent.toLowerCase().trim();--}}
{{--                if (!stockValue || stock === stockValue) {--}}
{{--                    row.style.display = '';--}}
{{--                } else {--}}
{{--                    row.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
