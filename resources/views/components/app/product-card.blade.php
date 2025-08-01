<div
    class="product-card bg-white rounded-xl shadow-lg overflow  overflow-hidden transition-transform duration-300 relative group"
    data-category="{{ $category }}">
    <div class="relative">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 sm:h-64 object-cover" loading="lazy"
             data-src="{{ $image }}">
        <span
            class="absolute top-4 right-4 bg-[var(--gold)] text-white text-xs px-3 py-1 rounded-full">{{ $badge }}</span>
        <button
            class="absolute bottom-4 right-4 bg-[var(--primary)] text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition"
            aria-label="Add to cart">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
        </button>
    </div>
    <div class="p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold mb-2">{{ $title }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ $price }}</p>
        <button
            class="quick-view-btn w-full bg-[var(--primary)] text-white py-2 rounded-lg hover:bg-[var(--primary-dark)] transition"
            data-product="{{ $productId }}">Quick View
        </button>
    </div>
    <script type="application/ld+json">
        @php
            echo json_encode([
                "@context" => "https://schema.org",
                "@type" => "Product",
                "name" => $title,
                "image" => $image,
                "description" => $description ?? 'Premium fashion item from Tarpor, blending tradition and style.',
                "sku" => $productId,
                "brand" => [
                    "@type" => "Brand",
                    "name" => "Tarpor"
                ],
                "offers" => [
                    "@type" => "Offer",
                    "priceCurrency" => "BDT",
                    "price" => preg_replace('/[^0-9.]/', '', $price),
                    "availability" => "https://schema.org/InStock",
                    "url" => url()->current()
                ]
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
        @endphp
    </script>
</div>
