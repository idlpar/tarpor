<!-- Enhanced Sticky CTA -->
@if(request()->is('/') || request()->is('shop'))
    <div id="sticky-cta" class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] text-white z-40 transform transition-transform duration-300 translate-y-full shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p class="text-sm sm:text-base font-medium">
                        <span class="hidden sm:inline">🚚 </span>Free Shipping on Orders Over <span class="font-bold">BDT 5,000!</span>
                        <a href="#shop" class="ml-2 underline hover:text-gray-200 font-semibold">Shop Now →</a>
                    </p>
                </div>
                <button id="close-cta" class="text-white hover:text-gray-200 p-1 rounded-full hover:bg-white/10 transition-colors duration-200" aria-label="Close banner">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
