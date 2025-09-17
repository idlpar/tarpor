<!-- Skip to Content Link for Keyboard Users -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:bg-[var(--primary)] focus:text-white focus:px-4 focus:py-2 focus:rounded">Skip to main content</a>

<!-- Navigation with ARIA Labels -->
<header class="fixed w-full z-50">
    <div class="nav-container py-4 px-4 sm:px-6 shadow-lg">
        <div class="container mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center" aria-label="Tarpor Home">
                @if (file_exists(public_path('logos/logo.svg')))
                    <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-10 sm:h-12 md:h-14 w-auto" loading="lazy">
                @else
                    <span class="font-brand text-xl sm:text-2xl text-white ml-2 hidden sm:inline">Tarpor</span>
                @endif
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-6" aria-label="Main navigation">
                <a href="#home" class="nav-link active text-white hover:text-[var(--primary)] font-medium" aria-current="page">Home</a>
                <a href="#kids" class="nav-link text-white hover:text-[var(--primary)] font-medium">Kids</a>
                <a href="#men" class="nav-link text-white hover:text-[var(--primary)] font-medium">Men</a>
                <a href="#collections" class="nav-link text-white hover:text-[var(--primary)] font-medium">Collections</a>
                <a href="#about" class="nav-link text-white hover:text-[var(--primary)] font-medium">About</a>
                <a href="#contact" class="nav-link text-white hover:text-[var(--primary)] font-medium">Contact</a>
            </nav>

            <div class="flex items-center space-x-4 sm:space-x-6">
                <button id="search-btn" class="text-white hover:text-[var(--primary)] transition" aria-label="Search products">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <button class="relative text-white hover:text-[var(--primary)] transition" aria-label="Wishlist (3 items)">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-[var(--primary)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">3</span>
                </button>

                <button class="relative text-white hover:text-[var(--primary)] transition" aria-label="Cart (5 items)">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-[var(--primary)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">5</span>
                </button>

                <button class="hidden md:flex items-center text-white hover:text-[var(--primary)] transition" aria-label="Login" onclick="window.location.href='{{ route('login') }}'; return false;">
                    <svg class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium text-sm">Login</span>
                </button>


                <button id="mobile-menu-btn" class="lg:hidden text-white transition" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden fixed inset-0 bg-white z-40 transform translate-x-full transition-transform duration-300" aria-hidden="true">
        <div class="container mx-auto px-6 py-8 h-full flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <a href="/" class="flex items-center" aria-label="Tarpor Home">
                    <img src="{{ asset('images/logo.png') }}" alt="Tarpor Logo" class="h-6" width="40" height="40">
                    <span class="font-brand text-2xl text-[var(--dark)]">Tarpor</span>
                </a>
                <button id="mobile-menu-close" class="text-[var(--dark)] transition" aria-label="Close mobile menu">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 flex flex-col space-y-6" aria-label="Mobile navigation">
                <a href="#home" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition" aria-current="page">Home</a>
                <a href="#kids" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition">Kids</a>
                <a href="#men" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition">Men</a>
                <a href="#collections" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition">Collections</a>
                <a href="#about" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition">About</a>
                <a href="#contact" class="text-2xl font-medium text-[var(--dark)] hover:text-[var(--primary)] transition">Contact</a>
            </nav>

            <div class="pt-8 border-t border-gray-100">
                <button class="w-full flex items-center justify-center text-[var(--dark)] hover:text-[var(--primary)] mb-4 transition">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium text-lg">Login / Register</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Search Modal -->
<div id="search-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-50" aria-modal="true" role="dialog" aria-label="Search products">
    <div class="search-modal bg-gray-800/95 rounded-xl max-w-3xl w-full mx-4 sm:mx-6 p-6 sm:p-8 transform scale-95 opacity-0">
        <button id="close-search" class="absolute top-4 right-4 bg-[var(--primary)] text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-[var(--primary-dark)] transition" aria-label="Close search">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="relative mb-6 mt-6">
            <label for="search-input" class="sr-only">Search products</label>
            <input type="text" id="search-input" placeholder="Search products..." class="w-full px-4 py-4 pl-12 border-none rounded-lg focus:outline-none focus:ring-4 focus:ring-[var(--primary)]/50 text-lg bg-gray-100">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-6 w-6 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-wrap gap-2">
                <span class="text-sm bg-gray-200 px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Kids Kurta</span>
                <span class="text-sm bg-gray-200 px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Men's Panjabi</span>
                <span class="text-sm bg-gray-200 px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Festive Wear</span>
            </div>
            <button class="text-[var(--primary)] hover:underline transition" aria-label="Cancel search">Cancel</button>
        </div>
    </div>
</div>
