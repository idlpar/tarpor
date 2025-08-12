<section id="best-sellers" class="py-12 sm:py-16 bg-[var(--ivory)]">
    <div class="container mx-auto px-4 sm:px-6">
        <h2 class="font-brand text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12 text-[var(--dark)]">Best Sellers</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @include('components.app.product-card', [
                'category' => 'kids',
                'image' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Kids Embroidered Kurta',
                'price' => 'BDT 1,800',
                'badge' => 'Best Seller',
                'productId' => 'kids-embroidered-kurta',
                'description' => 'Embroidered kurta for kids, ideal for festive occasions.'
            ])
            @include('components.app.product-card', [
                'category' => 'men',
                'image' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Men\'s Silk Panjabi',
                'price' => 'BDT 3,200',
                'badge' => 'Best Seller',
                'productId' => 'mens-silk-panjabi',
                'description' => 'Luxurious silk panjabi for men, perfect for special events.'
            ])
        </div>
    </div>
</section>
