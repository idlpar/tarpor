<div id="off-canvas-menu" class="off-canvas-menu lg:hidden" aria-hidden="true">
    <div class="flex flex-col h-full">
        <div class="flex justify-between items-center p-6 border-b border-gray-700">
            <a href="/" class="flex items-center" aria-label="Tarpor Home">
                @if (file_exists(public_path('logos/logo.svg')))
                    <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-10">
                @else
                    <span class="font-brand text-xl text-white ml-2">Tarpor</span>
                @endif
            </a>
            <button id="off-canvas-close" class="text-white" aria-label="Close mobile menu">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="flex-1 flex flex-col p-6 space-y-2">
            <a href="#home" class="menu-item text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg">Home</a>
            <div>
                <button class="menu-item w-full text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg flex justify-between items-center" aria-expanded="false" data-submenu="kids">
                    Kids
                    <svg class="chevron h-5 w-5 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu pl-6 space-y-2" id="kids-submenu">
                    <a href="#kids-kurtas" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Kurtas</a>
                    <a href="#kids-sherwanis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Sherwanis</a>
                    <a href="#kids-accessories" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Accessories</a>
                </div>
            </div>
            <div>
                <button class="menu-item w-full text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg flex justify-between items-center" aria-expanded="false" data-submenu="men">
                    Men
                    <svg class="chevron h-5 w-5 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="submenu pl-6 space-y-2" id="men-submenu">
                    <a href="#men-panjabis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Panjabis</a>
                    <a href="#men-sherwanis" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Sherwanis</a>
                    <a href="#men-accessories" class="block text-base text-gray-300 hover:text-[var(--primary)] py-1 px-4 rounded-lg">Accessories</a>
                </div>
            </div>
            <a href="#collections" class="menu-item text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg">Collections</a>
            <a href="#about" class="menu-item text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg">About</a>
            <a href="#contact" class="menu-item text-lg font-medium text-white hover:text-[var(--primary)] py-2 px-4 rounded-lg">Contact</a>
        </nav>
        <div class="p-6 border-t border-gray-700">
            <button class="w-full flex items-center justify-center text-white hover:text-[var(--primary)] py-2">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="font-medium text-base">Login / Register</span>
            </button>
        </div>
    </div>
</div>
