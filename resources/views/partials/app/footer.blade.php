<footer class="bg-gradient-to-br from-[var(--dark)] to-[var(--nav-bg)] text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            <!-- Brand Column -->
            <div class="flex flex-col items-center md:items-start">
                <a href="/" class="flex items-center mb-6">
                    @if (file_exists(public_path('logos/logo.svg')))
                        <img src="{{ asset('logos/logo.svg') }}" alt="Tarpor Logo" class="h-6 sm:h-8 w-auto" loading="lazy">
                    @else
                        <span class="font-brand text-2xl ml-2 text-white tracking-wide">Tarpor</span>
                    @endif
                </a>
                <p class="text-gray-300 text-sm mb-6 text-center md:text-left font-light leading-relaxed max-w-xs">
                    Premium fashion for kids and men, crafted for Bangladesh.
                </p>
                <div class="flex space-x-5">
                    <a href="https://www.facebook.com/tarpor" class="text-gray-400 hover:text-[var(--primary)] transition-colors duration-300" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/tarpor" class="text-gray-400 hover:text-[var(--primary)] transition-colors duration-300" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072 3.668.227 1.981 1.97 1.826 5.354.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.155 3.384 1.898 5.071 5.282 5.226 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c3.384-.155 5.071-1.842 5.226-5.226.058-1.28.072-1.689.072-4.948s-.014-3.668-.072-4.948c-.155-3.384-1.842-5.071-5.226-5.226C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                        </svg>
                    </a>
                    <a href="https://www.twitter.com/tarpor" class="text-gray-400 hover:text-[var(--primary)] transition-colors duration-300" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div class="text-center md:text-left">
                <h3 class="font-medium text-white uppercase tracking-wider text-sm mb-6">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="#home" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Home</a></li>
                    <li><a href="#kids" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Kids Collection</a></li>
                    <li><a href="#men" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Men's Collection</a></li>
                    <li><a href="#new-arrivals" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">New Arrivals</a></li>
                    <li><a href="#about" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Our Story</a></li>
                </ul>
            </div>

            <!-- Customer Service Column -->
            <div class="text-center md:text-left">
                <h3 class="font-medium text-white uppercase tracking-wider text-sm mb-6">Support</h3>
                <ul class="space-y-3">
                    <li><a href="#faq" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">FAQs</a></li>
                    <li><a href="#shipping" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Shipping Policy</a></li>
                    <li><a href="#returns" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Return Policy</a></li>
                    <li><a href="#privacy" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Privacy Policy</a></li>
                    <li><a href="#contact" class="text-gray-300 hover:text-[var(--primary)] transition-colors duration-300 text-sm font-light">Contact Us</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="text-center md:text-left">
                <h3 class="font-medium text-white uppercase tracking-wider text-sm mb-6">Newsletter</h3>
                <p class="text-gray-300 text-sm mb-4 font-light leading-relaxed">
                    Subscribe for exclusive offers, new arrivals and style tips.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="w-full">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input
                            type="email"
                            name="email"
                            placeholder="Your email address"
                            class="flex-grow px-4 py-2 rounded bg-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[var(--primary)] placeholder-gray-400"
                            required
                        >
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[var(--primary)] text-white text-sm font-medium rounded hover:bg-[var(--primary-dark)] transition-colors duration-300 whitespace-nowrap"
                        >
                            Subscribe
                        </button>
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs mt-1 text-left">{{ $message }}</p>
                    @enderror
                    @if(session('newsletter_success'))
                        <p class="text-green-400 text-xs mt-1 text-left">{{ session('newsletter_success') }}</p>
                    @endif
                    @if(session('newsletter_error'))
                        <p class="text-red-400 text-xs mt-1 text-left">{{ session('newsletter_error') }}</p>
                    @endif
                </form>
                <p class="text-gray-400 text-xs mt-3 font-light">
                    We respect your privacy. Unsubscribe at any time.
                </p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Payment Methods -->
                <div class="mb-4 md:mb-0">
                    <h4 class="text-gray-400 text-xs uppercase tracking-wider mb-3">We Accept</h4>
                    <div class="flex space-x-3">
                        <img src="{{ asset('images/payment-methods/visa.png') }}" alt="Visa" class="h-6" loading="lazy">
                        <img src="{{ asset('images/payment-methods/mastercard.png') }}" alt="Mastercard" class="h-6" loading="lazy">
                        <img src="{{ asset('images/payment-methods/bkash.png') }}" alt="bKash" class="h-6" loading="lazy">
                        <img src="{{ asset('images/payment-methods/nagad.png') }}" alt="Nagad" class="h-6" loading="lazy">
                    </div>
                </div>

                <!-- Copyright -->
                <p class="text-gray-400 text-sm font-light">
                    © {{ date('Y') }} Tarpor. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
