<div id="quick-view-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="modal bg-white rounded-xl max-w-4xl w-full mx-4 sm:mx-6 p-6 sm:p-8 transform scale-95 opacity-0">
        <button id="close-modal" class="absolute top-4 right-4 bg-[var(--primary)] text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-[var(--primary-dark)] transition" aria-label="Close modal">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col md:flex-row gap-6 sm:gap-8">
            <img src="" alt="" class="w-full md:w-1/2 h-48 sm:h-80 object-cover rounded-lg" id="modal-image" loading="lazy">
            <div class="flex-1">
                <h3 class="font-brand text-xl sm:text-2xl font-bold mb-4" id="modal-title"></h3>
                <p class="text-gray-600 text-sm sm:text-base mb-4" id="modal-price"></p>
                <p class="text-gray-600 text-sm sm:text-base mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <button class="bg-[var(--primary)] text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-[var(--primary-dark)] transition w-full">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
