<section id="festive" class="py-12 sm:py-16 bg-[var(--light)] festive-section relative">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="relative rounded-xl overflow-hidden shadow-lg bg-gradient-to-r from-[var(--dark)] to-[var(--nav-bg)] text-white py-12 sm:py-16 px-4 sm:px-8 text-center">
            <img src="https://images.unsplash.com/photo-1718433449771-4978672055b8?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Festive Collection Background" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="lazy" data-src="https://images.unsplash.com/photo-1718433449771-4978672055b8?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
            <div class="relative z-10">
                <h2 class="font-brand text-3xl sm:text-4xl md:text-5xl font-bold mb-6">Festive Collection 2025</h2>
                <p class="text-base sm:text-lg mb-8 max-w-2xl mx-auto">Celebrate in style with our exclusive festive wear for kids and men, designed to make every moment unforgettable.</p>
                <a href="#shop" class="btn-primary inline-block bg-[var(--primary)] text-white px-6 sm:px-8 py-3 rounded-full hover:bg-[var(--primary-dark)] transition">Shop Festive Collection</a>
            </div>
        </div>
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @include('components.app.product-card', [
                'category' => 'kids',
                'image' => 'https://images.unsplash.com/photo-1729861229315-4c9672f15a53?q=80&w=1946&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'title' => 'Kids Festive Kurta',
                'price' => 'BDT 1,500',
                'badge' => 'Festive',
                'productId' => 'kids-festive-kurta',
                'description' => 'Festive kurta for kids, ideal for celebrations.'
            ])
            @include('components.app.product-card', [
                'category' => 'men',
                'image' => 'https://plus.unsplash.com/premium_photo-1661540638251-a8e663bf45f8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTMyfHxmcmVlJTIwaW1hZ2VzfGVufDB8fDB8fHww',
                'title' => 'Men\'s Silk Panjabi',
                'price' => 'BDT 3,200',
                'badge' => 'Festive',
                'productId' => 'mens-silk-panjabi',
                'description' => 'Silk panjabi for men, perfect for festive occasions.'
            ])
        </div>
    </div>
</section>
