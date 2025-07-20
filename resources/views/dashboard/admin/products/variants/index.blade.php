@extends('layouts.admin')

@section('title', 'Manage All Product Variants | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="container mx-auto px-4 py-8">
        @include('components.breadcrumbs', [
            'links' => [
                'Products' => route('products.index'),
                'Variants' => null
            ]
        ])
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manage All Product Variants</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('product_attributes.index') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.827 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.827-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.827-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Manage Product Attributes
                </a>
                <button id="filterToggleButton" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                <div class="relative">
                    <select id="sortSelect" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 appearance-none pr-8">
                        <option value="name_asc">Name (A-Z)</option>
                        <option value="name_desc">Name (Z-A)</option>
                        <option value="variants_count_asc">Variants Count (Low to High)</option>
                        <option value="variants_count_desc">Variants Count (High to Low)</option>
                        <option value="date_desc">Date (Newest First)</option>
                        <option value="date_asc">Date (Oldest First)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div id="filterSection" class="bg-white p-4 rounded-lg shadow-md mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" id="searchInput" placeholder="Search by product name or SKU..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select id="categoryFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select id="brandFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <button id="clearFiltersButton" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">Clear Filters</button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white" id="productVariantsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Product Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">SKU</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Variants Count</th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="text-base font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->brand->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->sku }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->variants_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('products.variants.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-gray-100" title="Manage Variants">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h2 class="text-xl font-semibold text-gray-700">No Variable Products Found</h2>
                                        <p class="text-gray-500 mt-1">Only products marked as 'variable' will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterToggleButton = document.getElementById('filterToggleButton');
        const filterSection = document.getElementById('filterSection');
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const brandFilter = document.getElementById('brandFilter');
        const sortSelect = document.getElementById('sortSelect');
        const clearFiltersButton = document.getElementById('clearFiltersButton');

        filterToggleButton.addEventListener('click', () => {
            filterSection.classList.toggle('hidden');
        });

        function applyFiltersAndSort() {
            const params = new URLSearchParams();

            if (searchInput.value) {
                params.append('search', searchInput.value);
            }
            if (categoryFilter.value) {
                params.append('category_id', categoryFilter.value);
            }
            if (brandFilter.value) {
                params.append('brand_id', brandFilter.value);
            }
            if (sortSelect.value) {
                params.append('sort_by', sortSelect.value);
            }

            window.location.href = '{{ route('products.variants.index') }}?' + params.toString();
        }

        // Event Listeners for filters and sort
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                applyFiltersAndSort();
            }
        });
        categoryFilter.addEventListener('change', applyFiltersAndSort);
        brandFilter.addEventListener('change', applyFiltersAndSort);
        sortSelect.addEventListener('change', applyFiltersAndSort);

        clearFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            categoryFilter.value = '';
            brandFilter.value = '';
            sortSelect.value = 'date_desc'; // Reset to default sort
            applyFiltersAndSort();
        });

        // Set initial filter and sort values from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        if (urlParams.has('category_id')) {
            categoryFilter.value = urlParams.get('category_id');
        }
        if (urlParams.has('brand_id')) {
            brandFilter.value = urlParams.get('brand_id');
        }
        if (urlParams.has('sort_by')) {
            sortSelect.value = urlParams.get('sort_by');
        }

        // Show filter section if any filters are active
        if (urlParams.toString()) {
            filterSection.classList.remove('hidden');
        }
    });
</script>
@endpush