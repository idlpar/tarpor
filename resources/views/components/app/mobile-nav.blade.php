<div class="mobile-nav fixed bottom-0 left-0 right-0 z-40 md:hidden py-3 shadow-lg rounded-t-lg">
    <div id="mobile-search-input" class="mobile-search-input absolute bottom-full left-0 right-0 bg-[var(--dark)] p-4 hidden">
        <div class="relative">
            <input type="text" placeholder="Search products..." class="w-full px-4 py-3 pl-10 border-none rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--primary)] bg-[var(--secondary)]" aria-label="Search products">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
    <div class="container mx-auto px-4 flex justify-around items-center">
        <button id="mobile-search-btn" class="text-white hover:text-[var(--primary)]" aria-label="Search">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <button class="relative text-white hover:text-[var(--primary)]" aria-label="Wishlist">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">3</span>
        </button>
        <button class="relative text-white hover:text-[var(--primary)]" aria-label="Cart">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="absolute -top-2 -right-2 bg-[var(--red-badge)] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">5</span>
        </button>
        <button class="text-white hover:text-[var(--primary)]" aria-label="Login">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </button>
    </div>
</div>
