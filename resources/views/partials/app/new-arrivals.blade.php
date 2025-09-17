<section id="new-arrivals" class="py-12 sm:py-16 bg-[var(--light)]">
    <div class="container mx-auto px-4 sm:px-6">
        <h2 class="font-brand text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12 text-[var(--dark)]">New Arrivals</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @php
                $product1 = (object) [
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1594027859487-3cbd8092d9ef?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTExfHxmcmVlJTIwaW1hZ2VzfGVufDB8fDB8fHww',
                    'name' => 'Kids Festive Kurta',
                    'price' => 1500,
                    'sale_price' => null,
                    'category' => (object)['slug' => 'kids'],
                    'id' => 'kids-festive-kurta',
                    'short_description' => 'Vibrant festive kurta for kids, perfect for celebrations.',
                    'brand' => (object)['name' => 'Tarpor'],
                ];
                $product2 = (object) [
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                    'name' => 'Men\'s Premium Panjabi',
                    'price' => 2800,
                    'sale_price' => null,
                    'category' => (object)['slug' => 'men'],
                    'id' => 'mens-panjabi',
                    'short_description' => 'Premium panjabi for men, blending tradition and style.',
                    'brand' => (object)['name' => 'Tarpor'],
                ];
            @endphp
            <x-app.product-card :product="$product1" />
            <x-app.product-card :product="$product2" />
        </div>
    </div>
</section>
