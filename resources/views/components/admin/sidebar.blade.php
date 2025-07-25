<div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out" id="sidebar">
    <!-- Sidebar Header -->
    <div class="text-white flex items-center space-x-2 px-4">
        <i class="fas fa-cogs w-8 h-8"></i>
        <span class="text-2xl font-extrabold">Admin Panel</span>
    </div>

    <!-- Navigation -->
    <nav x-data="{ openDropdown: null }">
        <a href="{{ route('dashboard') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-blue-400' : '' }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
            Dashboard
        </a>

        <!-- Products Dropdown -->
        <div class="relative" x-data="{ id: 'productsDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-box-open w-5 h-5 mr-3"></i>
                    Products
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('products.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('products.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-list w-4 h-4 mr-3"></i>
                    All Products
                </a>
                <a href="{{ route('products.create') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('products.create') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-plus-circle w-4 h-4 mr-3"></i>
                    Add New Product
                </a>
                
                <a href="{{ route('product_attributes.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('product_attributes.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-cogs w-4 h-4 mr-3"></i>
                    Product Attributes
                </a>
                <a href="{{ route('collections.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('collections.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-layer-group w-4 h-4 mr-3"></i>
                    Product Collections
                </a>
                <a href="{{ route('labels.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('labels.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-bookmark w-4 h-4 mr-3"></i>
                    Product Labels
                </a>
            </div>
        </div>

        <!-- Product Specifications Dropdown -->
        <div class="relative" x-data="{ id: 'productSpecificationsDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-clipboard-list w-5 h-5 mr-3"></i>
                    Product Specifications
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('admin.product_specifications.groups.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.product_specifications.groups.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-layer-group w-4 h-4 mr-3"></i>
                    Groups
                </a>
                <a href="{{ route('admin.product_specifications.attributes.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.product_specifications.attributes.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-tags w-4 h-4 mr-3"></i>
                    Attributes
                </a>
                <a href="{{ route('admin.product_specifications.tables.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.product_specifications.tables.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-table w-4 h-4 mr-3"></i>
                    Tables
                </a>
            </div>
        </div>

        <!-- FAQs Dropdown -->
        <div class="relative" x-data="{ id: 'faqsDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
                    FAQs
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('faqs.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('faqs.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-list-alt w-4 h-4 mr-3"></i>
                    All FAQs
                </a>
                <a href="{{ route('faqs.create') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('faqs.create') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-plus-square w-4 h-4 mr-3"></i>
                    Add New
                </a>
            </div>
        </div>

        <!-- Brands Dropdown -->
        <div class="relative" x-data="{ id: 'brandsDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-copyright w-5 h-5 mr-3"></i>
                    Brands
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('brands.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('brands.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                    All Brands
                </a>
                <a href="{{ route('brands.create') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('brands.create') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-plus-square w-4 h-4 mr-3"></i>
                    Add New Brand
                </a>
            </div>
        </div>

        <!-- Orders Dropdown -->
        <div class="relative" x-data="{ id: 'ordersDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    Orders
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('admin.orders.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.orders.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-receipt w-4 h-4 mr-3"></i>
                    All Orders
                </a>
            </div>
        </div>

        <!-- Categories Dropdown -->
        <div class="relative" x-data="{ id: 'categoriesDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-sitemap w-5 h-5 mr-3"></i>
                    Categories
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('categories.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('categories.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-list-ul w-4 h-4 mr-3"></i>
                    All Categories
                </a>
                <a href="{{ route('categories.create') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('categories.create') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-folder-plus w-4 h-4 mr-3"></i>
                    Add New Category
                </a>
            </div>
        </div>

        <!-- Coupons Dropdown -->
        <div class="relative" x-data="{ id: 'couponsDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-ticket-alt w-5 h-5 mr-3"></i>
                    Coupons
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('coupons.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('coupons.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                    All Coupons
                </a>
                <a href="{{ route('coupons.create') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('coupons.create') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-plus-square w-4 h-4 mr-3"></i>
                    Add New Coupon
                </a>
            </div>
        </div>

        <!-- Newsletter Dropdown -->
        <div class="relative" x-data="{ id: 'newsletterDropdown' }"
             x-bind:class="{ 'bg-gray-700': openDropdown === id }">
            <button class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none"
                    x-on:click="openDropdown = (openDropdown === id) ? null : id">
                <div class="flex items-center flex-grow">
                    <i class="fas fa-envelope-open-text w-5 h-5 mr-3"></i>
                    Newsletter
                </div>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200"
                   x-bind:class="{ 'rotate-180': openDropdown === id }"></i>
            </button>
            <div x-show="openDropdown === id" x-collapse class="pl-6 mt-1 space-y-2">
                <a href="{{ route('admin.newsletter.subscribers.index') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.newsletter.subscribers.index') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-users w-4 h-4 mr-3"></i>
                    Subscribers
                </a>
                <a href="{{ route('admin.newsletter.send') }}" class="flex items-center py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.newsletter.send') ? 'bg-gray-700 text-blue-400' : '' }}">
                    <i class="fas fa-paper-plane w-4 h-4 mr-3"></i>
                    Send Newsletter
                </a>
            </div>
        </div>

        <!-- Single Menu Items -->
        <a href="{{ route('users.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('users.index') ? 'bg-gray-700 text-blue-400' : '' }}">
            <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
            Users
        </a>

        <a href="{{ route('gallery.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('gallery.index') ? 'bg-gray-700 text-blue-400' : '' }}">
            <i class="fas fa-images w-5 h-5 mr-3"></i>
            Gallery
        </a>

        <a href="{{ route('storage.link') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('storage.link') ? 'bg-gray-700 text-blue-400' : '' }}">
            <i class="fas fa-hdd w-5 h-5 mr-3"></i>
            Storage Link
        </a>
    </nav>
</div>
