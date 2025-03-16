@extends('layouts.admin')

@section('title', 'Add Product | ' . strtoupper(config('app.name')))

@push('styles')
    <!-- Preload styles to prevent FOUC -->
    <link rel="preload" href="{{ asset('ckeditor/content-styles.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('ckeditor/content-styles.css') }}"></noscript>

    <style>
        /* Hide the body until everything is loaded */
        body { visibility: hidden; }

        /* Loading spinner */
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Hover effects for buttons */
        .hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* CKEditor height */
        .ck-editor__editable {
            min-height: 300px !important;
        }

        /* Gradient background for the form */
        .gradient-bg {
            background: linear-gradient(135deg, #f9fafb, #e5e7eb);
        }
    </style>
@endpush

@section('page-content')

<!-- Loading spinner -->
<div id="loading-spinner" class="text-4xl text-blue-500">Loading...</div>

<!-- Breadcrumb Navigation -->
@include('components.breadcrumbs', [
    'links' => [
        'Dashboard' => route('admin.dashboard'),
        'Products' => route('product.index'),
        'Create Product' => null
    ]
])

<div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
    <form action="#" method="POST" class="w-full flex flex-col lg:flex-row gap-6">
    @csrf
        <!-- Left Column -->
        <div class="w-full lg:w-9/12">
            <!-- First Div (bg-white) -->
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold mb-6 text-gray-800">New Product</h2>

                <!-- Name -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 mb-2">Name *</label>
                    <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Product Name">
                </div>

                <!-- Permalink -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 mb-2">Permalink *</label>
                    <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://yourwebsite.com/products/">
                    <p class="text-sm text-gray-500 mt-2">Preview: <a href="#" class="text-blue-500 hover:underline">https://yourwebsite.com/products/</a></p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 mb-2">Description</label>
                    <textarea id="description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 mb-2">Content</label>
                    <textarea id="content" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Images -->
                <div class="mb-6 border border-dashed border-gray-400 p-6 rounded-lg text-center">
                    <label class="block font-semibold text-left text-gray-700 mb-4">Images</label>
                    <!-- Clickable Upload Box -->
                    <div class="border-dashed border-2 border-gray-300 p-10 rounded-lg cursor-pointer hover:bg-gray-50 transition-all flex flex-col items-center justify-center gap-3 h-40">
                        <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 8h.01"></path>
                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
                            <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
                            <path d="M16 19h6"></path>
                            <path d="M19 16v6"></path>
                        </svg>
                        <span class="text-gray-500 text-lg">Click here to add more images.</span>
                    </div>
                </div>
            </div>

            <!-- Second Div (bg-gray-300) -->
            <div class="bg-gray-300 p-6 rounded-lg shadow-lg mt-6">
                <!-- Specification Tables -->
                <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                    <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                        <label class="block font-semibold text-lg text-gray-700 mb-2">Specification Tables</label>
                        <select id="specificationDropdown" class="border text-sm p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">None</option>
                            <option value="general">General Specification</option>
                            <option value="technical">Technical Specification</option>
                        </select>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Setup meta title & description to make your site easy to discover on search engines such as Google.
                    </p>

                    <!-- Dynamic Specification Fields -->
                    <div id="specificationFields" class="bg-white p-4 rounded-lg shadow-md hidden mt-4">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">Group</th>
                                <th class="border p-3">Attribute</th>
                                <th class="border p-3">Attribute Value</th>
                            </tr>
                            </thead>
                            <tbody id="specTableBody">
                            <!-- Rows will be added dynamically here -->
                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- Overview -->
                <x-form.card label="Overview" class="bg-transparent">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKU -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">SKU</label>
                            <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="SKU-CZA-PZ-997">
                        </div>
                        <!-- Price -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Price</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                        </div>
                        <!-- Price Sale -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Price Sale</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                            <p class="text-sm text-gray-500 mt-2">Choose Discount Period</p>
                        </div>
                        <!-- Cost per Item -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Cost per Item</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                            <p class="text-sm text-gray-500 mt-2">Customers won't see this price.</p>
                        </div>
                        <!-- Barcode -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Barcode (ISBN, UPC, GTIN, etc.)</label>
                            <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter barcode">
                            <p class="text-sm text-gray-500 mt-2">Must be unique for each product.</p>
                        </div>
                    </div>
                </x-form.card>

                <!-- Stock Status -->
                <x-form.card label="Stock Status" class="bg-transparent">
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="stock_status" value="in_stock" class="mr-2">
                            <span class="text-gray-700">In Stock</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="stock_status" value="out_of_stock" class="mr-2">
                            <span class="text-gray-700">Out of Stock</span>
                        </label>
                    </div>
                </x-form.card>

                <!-- Shipping -->
                <x-form.card label="Shipping" class="bg-transparent">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Weight -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Weight (g)</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                        </div>
                        <!-- Length -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Length (cm)</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                        </div>
                        <!-- Width -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Width (cm)</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                        </div>
                        <!-- Height -->
                        <div>
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                        </div>
                    </div>
                </x-form.card>

                <!-- Attributes -->
                <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                    <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                        <label class="block text-xl font-bold text-gray-800">Attributes</label>
                        <button class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 ease-in-out shadow-lg hover:shadow-xl transform hover:scale-105">
                            Add Attribute
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Adding new attributes helps the product to have many options, such as size or color.
                    </p>
                </div>

                <!-- Product Options -->
                <x-form.card label="Product Options" class="bg-transparent">
                    <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Select Global Option</option>
                    </select>
                </x-form.card>

                <!-- Related Products -->
                <x-form.card label="Related Products" class="bg-transparent">
                    <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search products">
                </x-form.card>

                <!-- Cross-Selling Products -->
                <x-form.card label="Cross-Selling Products" class="bg-transparent">
                    <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search products">
                </x-form.card>

                <!-- Product FAQs -->
                <x-form.card label="Product FAQs" class="bg-transparent">
                    <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search or select from existing FAQs">
                </x-form.card>

                <!-- Search Engine Optimize -->
                <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                    <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                        <label class="block text-xl font-bold text-gray-800">Search Engine Optimize</label>
                        <button class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 ease-in-out shadow-lg hover:shadow-xl transform hover:scale-105">
                            Edit SEO Meta
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Setup meta title & description to make your site easy to discover on search engines such as Google.
                    </p>
                </div>


            </div>
        </div>
        <!-- Right Column -->
        <div class="w-full lg:w-3/12">
            <!-- Publish Card -->
            <div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Publish</h3>

                <!-- Save Buttons -->
                <div class="pt-4 border-t border-gray-200 flex gap-4">
                    <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover-effect">
                        Save
                    </button>
                    <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover-effect">
                        Save & Exit
                    </button>
                </div>
            </div>

            <!-- Status Card -->
            <x-form.card label="Status" required="true">
                <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Published</option>
                    <option>Draft</option>
                </select>
            </x-form.card>

            <!-- Store Card -->
            <x-form.card label="Store">
                <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select a store...</option>
                </select>
            </x-form.card>

            <!-- Is Featured? Card -->
            <x-form.card label="Is Featured?">
                <label class="relative inline-flex items-center cursor-pointer ml-2">
                    <input type="checkbox" class="sr-only peer" id="featuredToggle" disabled>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 peer-disabled:bg-gray-400"></div>
                </label>
            </x-form.card>


            <!-- Categories Card -->
            <x-form.card label="Categories">
                <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
            </x-form.card>

            <!-- Brand Card -->
            <x-form.card label="Brand">
                <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select a brand...</option>
                </select>
            </x-form.card>

            <!-- Featured Image Card -->
            <x-form.card label="Featured Image (Optional)">
                <div class="border-dashed border-2 border-gray-300 p-10 rounded-lg text-center cursor-pointer hover:bg-gray-50 transition-all flex flex-col items-center justify-center gap-3 h-40">
                    <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 8h.01"></path>
                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
                        <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
                        <path d="M16 19h6"></path>
                        <path d="M19 16v6"></path>
                    </svg>
                    <span class="text-gray-500 text-md">Choose Image</span>
                </div>
            </x-form.card>

            <!-- Product Collections Card -->
            <x-form.card label="Product Collections">
                <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="product_collections[]" value="new_arrival" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700 font-medium">New Arrival</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="product_collections[]" value="best_sellers" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700 font-medium">Best Sellers</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="product_collections[]" value="special_offer" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-gray-700 font-medium">Special Offer</span>
                    </label>
                </div>
            </x-form.card>

            <!-- Labels Card -->
            <x-form.card label="Labels">
                <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="labels[]" value="hot" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="text-gray-700 font-medium">Hot</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="labels[]" value="new" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-gray-700 font-medium">New</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="labels[]" value="sale" class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                        <span class="text-gray-700 font-medium">Sale</span>
                    </label>
                </div>
            </x-form.card>

            <!-- Minimum Order Quantity Card -->
            <x-form.card label="Minimum Order Quantity" required="true">
                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                <p class="text-sm text-gray-500 mt-2">Minimum quantity to place an order, if the value is 0, there is no limit.</p>
            </x-form.card>

            <!-- Maximum Order Quantity Card -->
            <x-form.card label="Maximum Order Quantity" required="true">
                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                <p class="text-sm text-gray-500 mt-2">Maximum quantity to place an order, if the value is 0, there is no limit.</p>
            </x-form.card>
        </div>

    </form>
</div>

@endsection
@push('scripts')
    <!-- CKEditor Script -->
    <script src="{{ asset('ckeditor/ckeditor.js') }}" defer></script>


    <!-- Dynamic Specification Table Script -->
    <script>
        function updateSpecificationFields() {
            const dropdown = document.getElementById("specificationDropdown");
            const specFields = document.getElementById("specificationFields");
            const specTableBody = document.getElementById("specTableBody");

            specTableBody.innerHTML = ""; // Clear previous entries

            if (dropdown.value === "") {
                specFields.classList.add("hidden"); // Hide if "None" is selected
                return;
            }

            specFields.classList.remove("hidden");

            const specifications = {
                general: [
                    { group: "General", attribute: "Brand" },
                    { group: "General", attribute: "Model" }
                ],
                technical: [
                    { group: "Battery", attribute: "Battery Life" },
                    { group: "Display", attribute: "Screen Size" },
                    { group: "Display", attribute: "Resolution", value: "1920×1080" }
                ]
            };

            // Add rows dynamically
            specifications[dropdown.value].forEach(spec => {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td class="border p-3">${spec.group}</td>
                <td class="border p-3">${spec.attribute}</td>
                <td class="border p-3">
                    <input type="text" class="w-full border rounded p-2" value="${spec.value || ''}">
                </td>
            `;
                specTableBody.appendChild(row);
            });
        }
    </script>

    <!-- Ensure content is visible only after full page load -->
    <script>
        // Show loading spinner while the page is loading
        document.getElementById("loading-spinner").style.display = "block";

        // Wait for all resources (stylesheets, scripts, etc.) to load
        window.addEventListener("load", function () {
            // Hide the loading spinner
            document.getElementById("loading-spinner").style.display = "none";

            // Make the body visible
            document.body.style.visibility = "visible";

            // Initialize CKEditor after the page fully loads
            setTimeout(() => {
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                                'blockQuote', 'imageUpload', 'insertTable', 'mediaEmbed', '|',
                                'undo', 'redo', 'code', 'codeBlock', 'strikethrough', 'underline', '|',
                                'alignment', 'fontSize', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'horizontalLine', 'indent', 'outdent', 'removeFormat', '|',
                                'selectAll', 'findAndReplace', 'sourceEditing', 'fullscreen'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        image: {
                            toolbar: [
                                'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', 'linkImage'
                            ]
                        },
                        language: 'en',
                        table: {
                            contentToolbar: [
                                'tableColumn', 'tableRow', 'mergeTableCells',
                                'insertTable', 'tableProperties', 'tableCellProperties'
                            ]
                        }
                    })
                    .then(editor => {
                        // Ensure the editor height is set correctly
                        editor.ui.view.editable.element.style.height = "300px";
                        editor.ui.view.editable.element.style.minHeight = "300px";
                    })
                    .catch(error => console.error(error));

                // Initialize CKEditor for the content textarea
                ClassicEditor
                    .create(document.querySelector('#content'), {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                                'blockQuote', 'imageUpload', 'insertTable', 'mediaEmbed', '|',
                                'undo', 'redo', 'code', 'codeBlock', 'strikethrough', 'underline', '|',
                                'alignment', 'fontSize', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'horizontalLine', 'indent', 'outdent', 'removeFormat', '|',
                                'selectAll', 'findAndReplace', 'sourceEditing', 'fullscreen'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        image: {
                            toolbar: [
                                'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', 'linkImage'
                            ]
                        },
                        language: 'en',
                        table: {
                            contentToolbar: [
                                'tableColumn', 'tableRow', 'mergeTableCells',
                                'insertTable', 'tableProperties', 'tableCellProperties'
                            ]
                        }
                    })
                    .then(editor => {
                        // Ensure the editor height is set correctly
                        editor.ui.view.editable.element.style.height = "300px";
                        editor.ui.view.editable.element.style.minHeight = "300px";
                    })
                    .catch(error => console.error(error));
            }, 100);
        });
    </script>
@endpush
