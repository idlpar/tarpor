<div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out" id="sidebar">
    <!-- Sidebar Header -->
    <div class="text-white flex items-center space-x-2 px-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.827 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.827-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.827-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        <span class="text-2xl font-extrabold">Admin Panel</span>
    </div>

    <!-- Navigation -->
    <nav>
        <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-blue-400' : '' }}">
            Dashboard
        </a>

        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none" onclick="toggleDropdown('productsDropdown')">
                Products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="productsDropdown" class="hidden pl-6 mt-1 space-y-2">
                <a href="{{ route('products.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('products.index') ? 'bg-gray-700 text-blue-400' : '' }}">All Products</a>
                <a href="{{ route('products.create') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('products.create') ? 'bg-gray-700 text-blue-400' : '' }}">Add New Product</a>
                <a href="{{ route('products.variants.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('products.variants.index') ? 'bg-gray-700 text-blue-400' : '' }}">Product Variants</a>
                <a href="{{ route('product_attributes.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('product_attributes.index') ? 'bg-gray-700 text-blue-400' : '' }}">Product Attributes</a>
                <a href="{{ route('collections.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('collections.index') ? 'bg-gray-700 text-blue-400' : '' }}">Product Collections</a>
                <a href="{{ route('labels.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('labels.index') ? 'bg-gray-700 text-blue-400' : '' }}">Product Labels</a>
            </div>
        </div>

        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none" onclick="toggleDropdown('ordersDropdown')">
                Orders
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="ordersDropdown" class="hidden pl-6 mt-1 space-y-2">
                <a href="{{ route('admin.orders.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.orders.index') ? 'bg-gray-700 text-blue-400' : '' }}">All Orders</a>
            </div>
        </div>

        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none" onclick="toggleDropdown('categoriesDropdown')">
                Categories
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="categoriesDropdown" class="hidden pl-6 mt-1 space-y-2">
                <a href="{{ route('categories.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('categories.index') ? 'bg-gray-700 text-blue-400' : '' }}">All Categories</a>
                <a href="{{ route('categories.create') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('categories.create') ? 'bg-gray-700 text-blue-400' : '' }}">Add New Category</a>
            </div>
        </div>

        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none" onclick="toggleDropdown('couponsDropdown')">
                Coupons
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="couponsDropdown" class="hidden pl-6 mt-1 space-y-2">
                <a href="{{ route('coupons.index') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('coupons.index') ? 'bg-gray-700 text-blue-400' : '' }}">All Coupons</a>
                <a href="{{ route('coupons.create') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('coupons.create') ? 'bg-gray-700 text-blue-400' : '' }}">Add New Coupon</a>
            </div>
        </div>

        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 focus:outline-none" onclick="toggleDropdown('newsletterDropdown')">
                Newsletter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="newsletterDropdown" class="hidden pl-6 mt-1 space-y-2">
                <a href="{{ route('admin.newsletter.subscribers') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.newsletter.subscribers') ? 'bg-gray-700 text-blue-400' : '' }}">Subscribers</a>
                <a href="{{ route('admin.newsletter.send') }}" class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('admin.newsletter.send') ? 'bg-gray-700 text-blue-400' : '' }}">Send Newsletter</a>
            </div>
        </div>

        <a href="{{ route('users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('users.index') ? 'bg-gray-700 text-blue-400' : '' }}">
            Users
        </a>

        <a href="{{ route('gallery.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('gallery.index') ? 'bg-gray-700 text-blue-400' : '' }}">
            Gallery
        </a>

        <a href="{{ route('storage.link') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-blue-400 {{ request()->routeIs('storage.link') ? 'bg-gray-700 text-blue-400' : '' }}">
            Storage Link
        </a>

    </nav>
</div>

<script>
    function toggleDropdown(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
</script>
