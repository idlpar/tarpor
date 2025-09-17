<div id="off-canvas-menu" class="off-canvas-menu lg:hidden" aria-hidden="true">
    <div class="flex flex-col h-full">
        <!-- Header with Logo and Close Button -->
        <div class="flex justify-between items-center p-6 border-b border-opacity-10 border-white">
            <a href="/" class="flex items-center" aria-label="Tarpor Home">
                @if (file_exists(public_path('logos/logo.svg')))
                    <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-8 w-auto">
                @else
                    <span class="font-brand text-2xl text-white tracking-wider">TARPOR</span>
                @endif
            </a>
            <button id="off-canvas-close" class="text-white hover:text-[var(--primary)] transition-colors duration-200" aria-label="Close mobile menu">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Main Navigation -->
        <nav class="flex-1 flex flex-col px-6 py-4 space-y-1 overflow-y-auto">
            <a href="#home" class="menu-item text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home
            </a>

            <!-- Kids Dropdown -->
            <div class="group">
                <button class="menu-item w-full text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg flex justify-between items-center transition-all duration-200" aria-expanded="false" data-submenu="kids">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kids
                    </div>
                    <svg class="chevron h-5 w-5 text-[var(--primary)] transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu pl-12 space-y-1 overflow-hidden" id="kids-submenu">
                    <a href="#kids-kurtas" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Kurtas
                    </a>
                    <a href="#kids-sherwanis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Sherwanis
                    </a>
                    <a href="#kids-accessories" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Accessories
                    </a>
                </div>
            </div>

            <!-- Men Dropdown -->
            <div class="group">
                <button class="menu-item w-full text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg flex justify-between items-center transition-all duration-200" aria-expanded="false" data-submenu="men">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Men
                    </div>
                    <svg class="chevron h-5 w-5 text-[var(--primary)] transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu pl-12 space-y-1 overflow-hidden" id="men-submenu">
                    <a href="#men-panjabis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Panjabis
                    </a>
                    <a href="#men-sherwanis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Sherwanis
                    </a>
                    <a href="#men-accessories" class="block text-base text-gray-300 hover:text-[var(--primary)] py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[var(--primary)] mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Accessories
                    </a>
                </div>
            </div>

            <a href="#collections" class="menu-item text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Collections
            </a>

            <a href="#about" class="menu-item text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                About
            </a>

            <a href="#contact" class="menu-item text-lg font-medium text-white hover:bg-white/5 py-3 px-4 rounded-lg transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contact
            </a>
        </nav>

        <!-- Footer with Login Button -->
        <div class="p-6 border-t border-opacity-10 border-white">
            <button class="w-full flex items-center justify-center space-x-2 bg-[var(--primary)] hover:bg-[var(--primary-dark)] text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Login / Register</span>
            </button>
        </div>
    </div>
</div>
