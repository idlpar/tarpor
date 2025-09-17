<div id="search-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-30">
    <div class="search-modal bg-[var(--dark)]/95 rounded-xl max-w-3xl w-full mx-4 sm:mx-6 p-6 sm:p-8 transform scale-95 opacity-0">
        <button id="close-search" class="absolute top-4 right-4 bg-[var(--red-close)] text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-[#B71C1C] transition z-30">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="relative mb-6">
            <input type="text" placeholder="Search products..." class="w-full px-4 py-4 pl-12 pr-12 border-none rounded-lg focus:outline-none focus:ring-4 focus:ring-[var(--primary)]/50 text-lg bg-[var(--secondary)]" aria-label="Search products">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-6 w-6 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <div class="flex justify-between items-center">
            <div class="flex flex-wrap gap-2">
                <span class="text-sm bg-[var(--secondary)] px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Kids Kurta</span>
                <span class="text-sm bg-[var(--secondary)] px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Men's Panjabi</span>
                <span class="text-sm bg-[var(--secondary)] px-3 py-1 rounded-full cursor-pointer hover:bg-[var(--primary)] hover:text-white transition">Festive Wear</span>
            </div>
            <button class="text-[var(--primary)] hover:underline" aria-label="Cancel search">Cancel</button>
        </div>
    </div>
</div>
