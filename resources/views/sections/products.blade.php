<section class="products">
    <!-- Header Section -->
    <div class="container mx-auto px-4 py-2 mb-0 lg:mb-4">
        <div class="row">
            <div class="col-lg-12 px-0 lg:px-2">
                <div class="flex justify-between items-center border-b border-gray-300 pb-0">

                    <!-- "Collections" with a premium color scheme -->
                    <div class="relative flex items-center group">
                        <h6 class="relative text-xs md:text-base text-white font-semibold px-4 py-1 lg:py-2
                    bg-gradient-to-r from-[#2c3e50] to-[#34495e] transform skew-x-[-6deg]
                    rounded-lg shadow-md transition-all duration-300
                    hover:from-[#273746] hover:to-[#2c3e50] hover:shadow-gray-500/50">
                            <span class="inline-block transform skew-x-[6deg]">Collections</span>
                        </h6>
                    </div>

                    <!-- "See all products" link with a gold hover effect -->
                    <a href="{{ route('shop.index') }}"
                       class="relative text-xs md:text-base text-[#2C3E50] font-medium transition-all duration-300
                           hover:text-[#1A5276] after:content-[''] after:absolute after:left-0 after:bottom-0
                               after:h-[2px] after:w-0 after:bg-[#2C3E50] after:transition-all after:duration-300
                               hover:after:w-full">
                        See all products →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 lg:px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 mx-auto gap-2">
            @foreach($products as $product)
                @include('partials.products.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
