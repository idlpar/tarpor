<header class="w-full z-50 fixed top-0">
    <div class="nav-container py-4 px-4 sm:px-6 shadow-lg bg-gray-950 ">
        <div class="container mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center" aria-label="Tarpor Home">
                @if (file_exists(public_path('logos/logo.svg')))
                    <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-6 w-auto sm:h-6 md:h-8 object-contain" loading="lazy">
                @else
                    <span class="font-brand text-xl sm:text-2xl text-white ml-2 hidden sm:inline">Tarpor</span>
                @endif
            </a>

            <nav class="hidden lg:flex items-center space-x-6" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="nav-link active text-white text-lg hover:text-[var(--primary)] font-medium">Home</a>
                <a href="{{ route('categories.show', 'kids') }}" class="nav-link text-white text-lg hover:text-[var(--primary)] font-medium">Kids</a>
                <a href="{{ route('categories.show', 'men') }}" class="nav-link text-white text-lg hover:text-[var(--primary)] font-medium">Men</a>
                <a href="{{ route('categories.index') }}" class="nav-link text-white text-lg hover:text-[var(--primary)] font-medium">Collections</a>
                <a href="{{ route('shop.index') }}" class="nav-link text-white text-lg hover:text-[var(--primary)] font-medium">Shop</a>
            </nav>

            <div class="flex items-center space-x-4 sm:space-x-6">
                                <button id="search-btn" class="hidden md:flex text-white hover:text-[var(--primary)]" aria-label="Search" data-tippy-content="Search" data-tippy-placement="bottom">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                @php
                    $wishlistCount = auth()->user() ? auth()->user()->wishlist->count() : 0;
                    $cartCount = session('cart') ? count(session('cart')) : 0;
                @endphp
                <a href="{{ route('wishlist.index') }}" class="hidden md:flex relative text-white hover:text-[var(--primary)]" aria-label="Wishlist" data-tippy-content="Wishlist" data-tippy-placement="bottom">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    @if($wishlistCount > 0)
                        <span id="wishlist-count" class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">{{ $wishlistCount }}</span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" class="hidden md:flex relative text-white hover:text-[var(--primary)]" aria-label="Cart" data-tippy-content="Cart" data-tippy-placement="bottom">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="cart-count absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs @if($cartCount == 0) hidden @endif">{{ $cartCount }}</span>
                </a>

                @guest
                    <button class="hidden md:flex items-center text-white hover:text-[var(--primary)]" aria-label="Login" onclick="window.location.href='{{ route('login') }}'; return false;">
                        <svg class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium text-sm">Login</span>
                    </button>
                @else
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="hidden md:flex items-center text-white hover:text-[var(--primary)] transition-all duration-300 group" aria-label="User menu" aria-expanded="false" x-bind:aria-expanded="open">
                            <div class="relative">
                                <!-- User Avatar -->
                                <div class="h-8 w-8 mr-2 rounded-full overflow-hidden bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                    @if (Auth::user()->profile_photo)
                                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/avatars/default-avatar.jpg') }}" alt="{{ Auth::user()->name }}'s avatar" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-white font-medium text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span class="absolute -bottom-0 -right-0 w-2.5 h-2.5 bg-green-500 rounded-full border border-white"></span>
                            </div>
                            <span class="font-medium text-sm truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 ml-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-bind:class="{ 'rotate-180': open, 'transform group-hover:translate-y-0.5': !open }">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl py-2 z-50 border border-gray-700/50 backdrop-blur-md overflow-hidden">

                            <!-- Decorative gradient overlay -->
                            <div class="absolute inset-0 bg-[radial-gradient(at_top_right,_var(--tw-gradient-stops))] from-blue-900/20 via-gray-800/20 to-blue-900/20 opacity-70 -z-10"></div>

                            <div class="relative">
                                <!-- User info header -->
                                <div class="px-4 py-3 border-b border-gray-700/30 bg-gradient-to-r from-blue-900/30 to-gray-800/30 flex items-center">
                                    <div class="h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center mr-3">
                                        @if (Auth::user()->profile_photo)
                                            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/avatar/default-avatar.jpg') }}" alt="{{ Auth::user()->name }}'s avatar" class="h-full w-full object-cover">
                                        @else
                                            <span class="text-white font-medium text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>

                                <!-- Menu items -->
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}"
                                       class="flex items-center px-4 py-2.5 text-sm text-gray-100 hover:bg-blue-900/50 hover:text-blue-300 transition-all duration-200 group">
                                        <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.show') }}"
                                       class="flex items-center px-4 py-2.5 text-sm text-gray-100 hover:bg-blue-900/50 hover:text-blue-300 transition-all duration-200 group">
                                        <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('orders.index') }}"
                                       class="flex items-center px-4 py-2.5 text-sm text-gray-100 hover:bg-blue-900/50 hover:text-blue-300 transition-all duration-200 group">
                                        <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Orders
                                    </a>
                                    <a href="{{ route('password.change.form') }}"
                                       class="flex items-center px-4 py-2.5 text-sm text-gray-100 hover:bg-blue-900/50 hover:text-blue-300 transition-all duration-200 group">
                                        <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Change Password
                                    </a>

                                    <!-- Dynamic Management Links -->
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
                                            <a href="{{ route($link['route']) }}"
                                               class="flex items-center px-4 py-2.5 text-sm text-gray-100 hover:bg-blue-900/50 hover:text-blue-300 transition-all duration-200 group">
                                                <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                                </svg>
                                                {{ $link['label'] }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Logout button -->
                                <div class="border-t border-gray-700/30 px-4 py-2 bg-gradient-to-r from-gray-800/50 to-gray-900/50">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left text-sm text-red-500 hover:text-red-600 hover:bg-blue-900/50 transition-all duration-200 group">
                                            <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Decorative right border -->
                            <div class="absolute top-0 right-0 w-1 h-full bg-gradient-to-b from-blue-600 to-blue-800 opacity-80"></div>
                        </div>
                    </div>
                @endguest
                <button id="mobile-menu-btn" class="lg:hidden text-white" aria-label="Toggle mobile menu">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
