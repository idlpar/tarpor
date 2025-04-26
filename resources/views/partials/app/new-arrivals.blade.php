<section id="new-arrivals" class="py-12 sm:py-16 bg-[var(--light)]">
    <div class="container mx-auto px-4 sm:px-6">
        <h2 class="font-brand text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12 text-[var(--dark)]">New Arrivals</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @include('components.app.product-card', [
                'category' => 'kids',
                'image' => 'https://images.unsplash.com/photo-1594027859487-3cbd8092d9ef?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTExfHxmcmVlJTIwaW1hZ2VzfGVufDB8fDB8fHww',
                'title' => 'Kids Festive Kurta',
                'price' => 'BDT 1,500',
                'badge' => 'New',
                'productId' => 'kids-festive-kurta',
                'description' => 'Vibrant festive kurta for kids, perfect for celebrations.'
            ])
            @include('components.app.product-card', [
                'category' => 'men',
                'image' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Men\'s Premium Panjabi',
                'price' => 'BDT 2,800',
                'badge' => 'New',
                'productId' => 'mens-panjabi',
                'description' => 'Premium panjabi for men, blending tradition and style.'
            ])
        </div>
    </div>
</section>
