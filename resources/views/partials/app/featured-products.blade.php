<section id="featured" class="py-12 sm:py-16 bg-gradient-to-b from-[var(--ivory)] to-[var(--light)]">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-12">
            <h2 class="font-brand text-2xl sm:text-3xl md:text-4xl font-bold text-[var(--dark)]">Featured Products</h2>
            <div class="flex space-x-2 sm:space-x-4 mt-4 sm:mt-0">
                <button class="filter-btn px-3 sm:px-4 py-2 rounded-lg bg-[var(--secondary)] hover:bg-[var(--primary)] hover:text-white transition" data-filter="all">All</button>
                <button class="filter-btn px-3 sm:px-4 py-2 rounded-lg bg-[var(--secondary)] hover:bg-[var(--primary)] hover:text-white transition" data-filter="kids">Kids</button>
                <button class="filter-btn px-3 sm:px-4 py-2 rounded-lg bg-[var(--secondary)] hover:bg-[var(--primary)] hover:text-white transition" data-filter="men">Men</button>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @include('components.app.product-card', [
                'category' => 'kids',
                'image' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Kids Cotton Kurta',
                'price' => 'BDT 1,200',
                'badge' => 'New',
                'productId' => 'kids-kurta',
                'description' => 'Comfortable cotton kurta for kids, suitable for daily wear.'
            ])
        </div>
    </div>
</section>
