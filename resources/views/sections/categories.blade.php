<section class="categories">
    <!-- Header Section -->
    <div class="container mx-auto px-4 py-2 mb-2 lg:mb-6">
        <div class="row">
            <div class="col-lg-12 px-0 lg:px-2">
                <div class="flex justify-between items-center border-b border-gray-300 pb-0">

                    <!-- "Top Categories" with a premium color scheme -->
                    <div class="relative flex items-center group">
                        <h6 class="relative text-xs md:text-base text-white font-semibold px-4 py-1 lg:py-2
                    bg-gradient-to-r from-[#2c3e50] to-[#34495e] transform skew-x-[-6deg]
                    rounded-lg shadow-md transition-all duration-300
                    hover:from-[#273746] hover:to-[#2c3e50] hover:shadow-gray-500/50">
                            <span class="inline-block transform skew-x-[6deg]">Top Categories</span>
                        </h6>
                    </div>

                    <!-- "See all categories" link with a gold hover effect -->
                    <!-- "See all categories" link with a professional blue hover effect -->
                    <a href="/categories"
                       class="relative text-xs md:text-base text-[#2C3E50] font-medium transition-all duration-300
                               hover:text-[#1A5276] after:content-[''] after:absolute after:left-0 after:bottom-0
                                   after:h-[2px] after:w-0 after:bg-[#2C3E50] after:transition-all after:duration-300
                                   hover:after:w-full">
                        See all categories →
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Products List -->
    <div class="container mx-auto px-2 lg:px-4">
        <ul class="flex justify-between overflow-x-auto space-x-2 lg:space-x-4 py-2 lg:py-4 scrollbar-hide">
            <!-- Product Item 1 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/laptop-all-laptop" class="text-center">
                    <img src="{{ asset('uploads/category-icon/Laptop.svg') }}" loading="lazy" alt="Laptop"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/laptop-all-laptop"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Laptop
                </a>
            </li>

            <!-- Product Item 2 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/desktop-component-processor" class="text-center">
                    <img src="{{ asset('uploads/category-icon/Processor.svg') }}" loading="lazy" alt="Processor"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/desktop-component-processor"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Processor
                </a>
            </li>

            <!-- Product Item 3 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/desktop-pc-all-in-one-pc" class="text-center">
                    <img src="{{ asset('uploads/category-icon/all-in-one.svg') }}" loading="lazy" alt="AIO PC"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/desktop-pc-all-in-one-pc"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    AIO PC
                </a>
            </li>

            <!-- Product Item 4 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/audio-video-speaker" class="text-center">
                    <img src="{{ asset('uploads/category-icon/speaker.svg') }}" loading="lazy" alt="Speaker"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/audio-video-speaker"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Speaker
                </a>
            </li>

            <!-- Product Item 5 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/monitor-all-monitor" class="text-center">
                    <img src="{{ asset('uploads/category-icon/Monitor.svg') }}" loading="lazy" alt="Monitor"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/monitor-all-monitor"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Monitor
                </a>
            </li>

            <!-- Product Item 6 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/software" class="text-center">
                    <img src="{{ asset('uploads/category-icon/software.svg') }}" loading="lazy" alt="Software"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/software"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Software
                </a>
            </li>

            <!-- Product Item 7 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/gaming" class="text-center">
                    <img src="{{ asset('uploads/category-icon/gaming.svg') }}" loading="lazy" alt="Gaming"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/gaming"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Gaming
                </a>
            </li>

            <!-- Product Item 8 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/all-laser-and-ink-printer" class="text-center">
                    <img src="{{ asset('uploads/category-icon/printer.svg') }}" loading="lazy" alt="Printer"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/all-laser-and-ink-printer"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Printer
                </a>
            </li>

            <!-- Product Item 9 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/desktop-component-graphics-card" class="text-center">
                    <img src="{{ asset('uploads/category-icon/GPU.svg') }}" loading="lazy" alt="GPU"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/desktop-component-graphics-card"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    GPU
                </a>
            </li>

            <!-- Product Item 10 -->
            <li class="flex flex-col items-center group min-w-[60px] md:min-w-[80px] lg:min-w-[100px]">
                <a href="/category/camera" class="text-center">
                    <img src="{{ asset('uploads/category-icon/camera.svg') }}" loading="lazy" alt="Camera"
                         class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </a>
                <a href="/category/camera"
                   class="text-center mt-0 lg:mt-2 text-xs sm:text-sm md:text-md text-gray-800 transition-all duration-300 group-hover:text-purple-600 group-hover:font-semibold">
                    Camera
                </a>
            </li>
        </ul>
    </div>
</section>
