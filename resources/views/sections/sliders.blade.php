<section class="sliders-ads mt-2 lg:mt-3">
    <div class="container mx-auto px-2 md:px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-1"> <!-- Constrain height -->
            <!-- Main Slider (Left Side) -->
            <div class="lg:col-span-8 col-span-12 h-full">
                <div id="carousel" class="relative overflow-hidden rounded-lg h-full">
                    <!-- Slide Container -->
                    <div id="slide-container" class="flex transition-transform duration-500 ease-in-out h-full">
                        <!-- Slide 1 -->
                        <div class="slide min-w-full h-full" data-slideindex="0">
                            <a href="/dji-flip-drone-with-dji-rc-2-remote">
                                <img
                                    src="{{ asset('uploads/slides/slide-1.webp') }}"
                                    loading="lazy"
                                    alt="DJI Flip Drone"
                                    class="w-full h-full object-cover rounded-lg"
                                />
                            </a>
                        </div>
                        <!-- Slide 2 -->
                        <div class="slide min-w-full h-full" data-slideindex="1">
                            <a href="/offers/falgun-fest">
                                <img
                                    src="{{ asset('uploads/slides/slide-2.webp') }}"
                                    loading="lazy"
                                    alt="Falgun Fest"
                                    class="w-full h-full object-cover rounded-lg"
                                />
                            </a>
                        </div>
                    </div>

                    <!-- Previous and Next Buttons -->
                    <button id="prev-button" class="absolute left-1 lg:left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-25 text-white p-3 rounded-full hover:bg-opacity-75 transition-opacity">
                        <i class="fa-solid fa-caret-left"></i>
                    </button>
                    <button id="next-button" class="absolute right-1 lg:right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-25 text-white p-3 rounded-full hover:bg-opacity-75 transition-opacity">
                        <i class="fa-solid fa-caret-right"></i>
                    </button>

                    <!-- Slide Indicators (Rectangular Tiles) -->
                    <div class="hidden md:flex absolute bottom-4 left-1/2 transform -translate-x-1/2 space-x-1">
                        <div class="slide-indicator w-12 h-1 my-4 bg-black opacity-80 cursor-pointer transition-opacity relative before:absolute before:-top-2 before:-bottom-2 before:-left-2 before:-right-2 before:content-['']" data-slideindex="0"></div>
                        <div class="slide-indicator w-12 h-1 my-4 bg-black opacity-80 cursor-pointer transition-opacity relative before:absolute before:-top-2 before:-bottom-2 before:-left-2 before:-right-2 before:content-['']" data-slideindex="1"></div>
                    </div>
                </div>
            </div>

            <!-- Right Side Ads (Desktop) -->
            <div class="lg:col-span-4 col-span-12 hidden lg:block h-full">
                <div class="flex flex-col h-full gap-1">
                    <!-- Ad 1 -->
                    <div class="flex-1 overflow-hidden rounded-lg">
                        <a href="/store/logitech">
                            <img
                                src="{{ asset('uploads/ads/ad-1.webp') }}"
                                loading="lazy"
                                alt="Logitech Surprise Discount"
                                class="w-full h-full object-cover rounded-lg"
                            />
                        </a>
                    </div>
                    <!-- Ad 2 -->
                    <div class="flex-1 overflow-hidden rounded-lg">
                        <a href="/hohem-mic-01-2tx-plus-1rx-microphone">
                            <img
                                src="{{ asset('uploads/ads/ad-2.webp') }}"
                                loading="lazy"
                                alt="Hohem MIC-01 (2TX + 1RX)"
                                class="w-full h-full object-cover rounded-lg"
                            />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side Ads (Mobile) -->
            <div class="col-span-12 lg:hidden flex gap-1">
                <!-- Ad 1 -->
                <div class="w-1/2 overflow-hidden rounded-lg">
                    <a href="/store/logitech">
                        <img
                            src="{{ asset('uploads/ads/ad-1.webp') }}"
                            alt="Logitech Surprise Discount"
                            class="w-full h-full object-cover rounded-lg"
                        />
                    </a>
                </div>
                <!-- Ad 2 -->
                <div class="w-1/2 overflow-hidden rounded-lg">
                    <a href="/hohem-mic-01-2tx-plus-1rx-microphone">
                        <img
                            src="{{ asset('uploads/ads/ad-2.webp') }}"
                            loading="lazy"
                            alt="Hohem MIC-01 (2TX + 1RX)"
                            class="w-full h-full object-cover rounded-lg"
                        />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
