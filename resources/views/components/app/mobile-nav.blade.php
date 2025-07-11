<div class="mobile-nav fixed bottom-0 left-0 right-0 z-40 md:hidden py-3 shadow-lg rounded-t-lg bg-gray-900">
    <div id="mobile-search-input" class="mobile-search-input absolute bottom-full left-0 right-0 bg-[var(--dark)] p-4 hidden">
        <div class="relative">
            <input type="text" placeholder="Search products..." class="w-full px-4 py-3 pl-10 border-none rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--primary)] bg-[var(--secondary)]" aria-label="Search products">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
    <div id="mobile-nav-menu" class="mobile-nav-menu absolute bottom-full left-0 right-0 bg-gray-900 p-4 hidden">
        <nav class="flex flex-col space-y-2" aria-label="Mobile navigation">
            <a href="{{ route('home') }}" class="text-white text-lg hover:text-[var(--primary)] font-medium py-2">Home</a>
            <a href="{{ route('categories.show', 'kids') }}" class="text-white text-lg hover:text-[var(--primary)] font-medium py-2">Kids</a>
            <a href="{{ route('categories.show', 'men') }}" class="text-white text-lg hover:text-[var(--primary)] font-medium py-2">Men</a>
            <a href="{{ route('categories.index') }}" class="text-white text-lg hover:text-[var(--primary)] font-medium py-2">Collections</a>
            <a href="{{ route('shop.index') }}" class="text-white text-lg hover:text-[var(--primary)] font-medium py-2">Shop</a>
        </nav>
    </div>
    <div class="container mx-auto px-4 flex justify-around items-center">
{{--        <button id="mobile-menu-btn" class="text-white hover:text-[var(--primary)]" aria-label="Toggle menu">--}}
{{--            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />--}}
{{--            </svg>--}}
{{--        </button>--}}
        <button id="mobile-search-btn" class="text-white hover:text-[var(--primary)]" aria-label="Search" data-tippy-content="Search" data-tippy-placement="bottom">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        @php
            $wishlistCount = auth()->user() ? auth()->user()->wishlist->count() : 0;
            $cartCount = session('cart') ? count(session('cart')) : 0;
        @endphp
        <a href="{{ route('wishlist.index') }}" class="relative text-white hover:text-[var(--primary)]" aria-label="Wishlist" data-tippy-content="Wishlist" data-tippy-placement="bottom">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            @if($wishlistCount > 0)
                <span id="wishlist-count" class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">{{ $wishlistCount }}</span>
            @endif
        </a>
        <button class="relative text-white hover:text-[var(--primary)]" aria-label="Cart" data-tippy-content="Cart" data-tippy-placement="bottom">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="cart-count absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs @if($cartCount == 0) hidden @endif">{{ $cartCount }}</span>
        </button>
        @guest
            <button class="text-white hover:text-[var(--primary)]" aria-label="Login" onclick="window.location.href='{{ route('login') }}'; return false;">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>
        @else
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center text-white hover:text-[var(--primary)]" aria-label="User menu" aria-expanded="false" x-bind:aria-expanded="open">
                    <svg class="h-6 w-6 mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 21a7 7 0 0114 0H5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="2" fill="var(--primary)"/>
                    </svg>
                    <span class="font-medium text-sm truncate max-w-[80px]">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-bind:class="{ 'rotate-180': open }">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-0 mb-2 w-64 bg-gradient-to-br from-gray-800 to-gray-900 rounded-md shadow-lg py-1 z-50">
                    <!-- Menu items with icons -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-blue-900/50 transition-colors duration-200 group">
                        <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-blue-900/50 transition-colors duration-200 group">
                        <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-blue-900/50 transition-colors duration-200 group">
                        <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Orders
                    </a>
                    <a href="{{ route('password.change.form') }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-blue-900/50 transition-colors duration-200 group">
                        <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Change Password
                    </a>

                    <!-- Management Links -->
                    @php
                        $managementLinks = [
                            'admin' => [
                                ['route' => 'users.index', 'label' => 'Manage Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['route' => 'orders.index', 'label' => 'Manage Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                                ['route' => 'products.index', 'label' => 'Manage Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                                ['route' => 'categories.index', 'label' => 'Manage Categories', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                            ],
                            'staff' => [
                                ['route' => 'products.index', 'label' => 'Manage Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                                ['route' => 'categories.index', 'label' => 'Manage Categories', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                            ],
                            'user' => [],
                        ];
                        $userRole = Auth::user()->role;
                        $links = $managementLinks[$userRole] ?? [];
                    @endphp
                    @if (!empty($links))
                        <div class="border-t border-gray-700/30 mx-4 my-2"></div>
                        <div class="px-4 py-1 text-xs text-blue-300 font-medium uppercase tracking-wider">Management</div>
                        @foreach ($links as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-blue-900/50 transition-colors duration-200 group">
                                <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    @endif

                    <!-- Logout Button -->
                    <div class="border-t border-gray-700/30 mx-4 my-2"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-blue-900/50 hover:text-red-600 transition-colors duration-200 group">
                            <svg class="h-5 w-5 mr-3 text-gray-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</div>
