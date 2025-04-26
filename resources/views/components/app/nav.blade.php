<header class="fixed w-full z-50">
    <div class="nav-container py-4 px-4 sm:px-6 shadow-lg">
        <div class="container mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center" aria-label="Tarpor Home">
                @if (file_exists(public_path('logos/logo.svg')))
                    <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-10 sm:h-12" loading="lazy">
                @else
                    <span class="font-brand text-xl sm:text-2xl text-white ml-2 hidden sm:inline">Tarpor</span>
                @endif
            </a>

            <nav class="hidden lg:flex items-center space-x-6" aria-label="Main navigation">
                <a href="#home" class="nav-link active text-white hover:text-[var(--primary)] font-medium">Home</a>
                <a href="#kids" class="nav-link text-white hover:text-[var(--primary)] font-medium">Kids</a>
                <a href="#men" class="nav-link text-white hover:text-[var(--primary)] font-medium">Men</a>
                <a href="#collections" class="nav-link text-white hover:text-[var(--primary)] font-medium">Collections</a>
                <a href="#about" class="nav-link text-white hover:text-[var(--primary)] font-medium">About</a>
                <a href="#contact" class="nav-link text-white hover:text-[var(--primary)] font-medium">Contact</a>
            </nav>

            <div class="flex items-center space-x-4 sm:space-x-6">
                <button id="search-btn" class="hidden md:flex text-white hover:text-[var(--primary)]" aria-label="Search">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button class="hidden md:flex relative text-white hover:text-[var(--primary)]" aria-label="Wishlist">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">3</span>
                </button>
                <button class="hidden md:flex relative text-white hover:text-[var(--primary)]" aria-label="Cart">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">5</span>
                </button>
                <button class="hidden md:flex items-center text-white hover:text-[var(--primary)]" aria-label="Login">
                    <svg class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium text-sm">Login</span>
                </button>
                <button id="mobile-menu-btn" class="lg:hidden text-white" aria-label="Toggle mobile menu">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
