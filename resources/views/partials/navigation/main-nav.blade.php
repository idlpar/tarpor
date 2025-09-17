{{--<!-- Main Navigation -->--}}
{{--<nav class="bg-gray-950 text-white sticky top-0 py-2 lg:py-0 z-50">--}}
{{--    <div class="container mx-auto px-4 flex justify-between items-center">--}}
{{--        <!-- Hamburger Menu (Mobile Only) -->--}}
{{--        <div class="lg:hidden w-1/6 pt-2 flex items-center">--}}
{{--            <button class="text-white" id="open-mobile-menu" aria-label="Open menu">--}}
{{--                <i class="fas fa-bars text-2xl"></i>--}}
{{--            </button>--}}
{{--        </div>--}}

{{--        <!-- Mobile Sidebar -->--}}
{{--        <div id="mobile-menu" class="fixed inset-0 left-0 w-64 h-full bg-gray-950 text-white text-left p-4 transform -translate-x-full transition-transform duration-300 lg:hidden z-50">--}}
{{--            <button id="close-menu" class="absolute top-4 right-4 text-white text-2xl cursor-pointer">&times;</button>--}}
{{--            <ul id="mobile-menu-list" class="mt-8 space-y-2"></ul>--}}
{{--        </div>--}}

{{--        <!-- Logo -->--}}
{{--        <div class="w-1/3 lg:w-3/12 pt-2 md:pt-0 flex justify-start">--}}
{{--            <a href="{{ route('home') }}" aria-label="Home">--}}
{{--                <img src="{{ asset('/logos/logo.svg') }}" loading="lazy" alt="TARPOR" class="w-auto h-10">--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <!-- Search Box (Centered) -->--}}
{{--        <div class="w-6/12 lg:w-6/12 px-4 py-2 hidden lg:flex relative">--}}
{{--            <form class="w-full flex items-center" role="search">--}}
{{--                <input type="search" id="user-search-box" class="w-full px-4 py-2 bg-gray-50 text-gray-900 rounded-full border border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-16" autocomplete="off" placeholder="Enter Your Keyword..." aria-label="Search products" />--}}
{{--                <button class="absolute right-0 top-0 bottom-0 bg-lime-500 text-white px-10 py-0 my-2 mr-4 rounded-r-full" type="submit" aria-label="Search">--}}
{{--                    <i class="fas fa-search"></i>--}}
{{--                </button>--}}
{{--            </form>--}}

{{--            <!-- Search Results Dropdown -->--}}
{{--            <div id="search-dropdown" class="absolute top-full left-0 right-0 mx-4 max-w-full bg-white shadow-md rounded-lg -mt-2 hidden overflow-hidden">--}}
{{--                <ul id="search-results" class="py-2 mx-4 text-gray-900"></ul>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Cart, Wishlist, and User Account -->--}}
{{--        <div class="w-1/3 lg:w-3/12 flex items-center justify-end space-x-2 lg:space-x-5 pt-4">--}}
{{--            <!-- Search Button (Mobile Only) -->--}}
{{--            <div class="relative group -mt-2 md:mt-0">--}}
{{--                <button class="lg:hidden text-white cursor-pointer" id="toggle-search" aria-label="Search">--}}
{{--                    <i class="fas fa-search"></i>--}}
{{--                </button>--}}
{{--                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">--}}
{{--                      Search--}}
{{--                    </span>--}}
{{--            </div>--}}

{{--            <!-- Wishlist Button -->--}}
{{--            <div class="relative group -mt-2 md:mt-0">--}}
{{--                <a href="/wishlist" class="text-white hover:text-lime-500 relative" aria-label="Wishlist">--}}
{{--                    <i class="fas fa-heart"></i>--}}
{{--                    <span class="absolute -top-3 -right-3 bg-red-500 text-white text-xs rounded-full px-2 py-1">0</span>--}}
{{--                </a>--}}
{{--                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">--}}
{{--                      Wishlist--}}
{{--                    </span>--}}
{{--            </div>--}}

{{--            <!-- Compare Button -->--}}
{{--            <div class="relative group -mt-2 md:mt-0">--}}
{{--                <button class="text-white hover:text-lime-500 cursor-pointer relative" aria-label="Compare">--}}
{{--                    <i class="fas fa-exchange-alt"></i>--}}
{{--                    <span class="absolute -top-3 -right-3 bg-red-500 text-white text-xs rounded-full px-2 py-1">0</span>--}}
{{--                </button>--}}
{{--                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">--}}
{{--                      Compare--}}
{{--                    </span>--}}
{{--            </div>--}}

{{--            <!-- Cart Button -->--}}
{{--            <div class="relative group -mt-2 md:mt-0">--}}
{{--                <button class="text-white hover:text-lime-500 cursor-pointer relative" aria-label="Cart">--}}
{{--                    <i class="fas fa-cart-arrow-down"></i>--}}
{{--                    <span class="absolute -top-3 -right-3 bg-red-500 text-white text-xs rounded-full px-2 py-1">0</span>--}}
{{--                </button>--}}
{{--                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">--}}
{{--                      Cart--}}
{{--                    </span>--}}
{{--            </div>--}}


{{--            <!-- User Account Button (Desktop Only) -->--}}
{{--            <div class="relative group hidden md:block" x-data="{ open: false }">--}}
{{--                @auth--}}
{{--                    <!-- User Account Button -->--}}
{{--                    <button--}}
{{--                        @click="open = !open"--}}
{{--                        class="text-white hover:text-lime-500 relative focus:outline-none"--}}
{{--                        aria-label="User Account"--}}
{{--                    >--}}
{{--                        <span class="inline-block">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--                            <g id="icon-user-man">--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(96.470588%,74.901962%,21.960784%);fill-opacity:1;" d="M 15.5 2.5 C 15.5625 2.722656 15.625 2.945312 15.6875 3.171875 C 15.949219 4.015625 15.949219 4.015625 16.527344 4.605469 C 17.15625 5.464844 17.09375 6.011719 17.046875 7.0625 C 17.023438 7.625 17.007812 8.1875 17 8.75 C 17.082031 8.832031 17.164062 8.914062 17.25 9 C 17.285156 9.667969 17.261719 10.332031 17.25 11 C 17.003906 11 16.753906 11 16.5 11 C 16.535156 11.246094 16.570312 11.496094 16.609375 11.75 C 16.621094 12.699219 16.269531 13.121094 15.640625 13.8125 C 15.402344 14.054688 15.402344 14.054688 15.15625 14.300781 C 14.695312 14.75 14.695312 14.75 14.5 15.5 C 14.640625 15.519531 14.777344 15.539062 14.921875 15.558594 C 16.527344 15.832031 17.546875 16.414062 18.75 17.5 C 18.882812 17.609375 19.015625 17.722656 19.152344 17.835938 C 19.738281 18.402344 19.988281 18.894531 20.085938 19.695312 C 20.09375 20.390625 20.082031 21.058594 20 21.75 C 19.554688 22.195312 18.902344 22.03125 18.304688 22.03125 C 17.992188 22.03125 17.683594 22.035156 17.363281 22.035156 C 17.023438 22.035156 16.683594 22.035156 16.34375 22.03125 C 15.996094 22.035156 15.652344 22.035156 15.304688 22.035156 C 14.578125 22.035156 13.851562 22.035156 13.121094 22.03125 C 12.1875 22.03125 11.253906 22.03125 10.320312 22.035156 C 9.605469 22.035156 8.890625 22.035156 8.171875 22.035156 C 7.828125 22.035156 7.484375 22.035156 7.140625 22.035156 C 6.660156 22.035156 6.179688 22.035156 5.695312 22.03125 C 5.285156 22.03125 5.285156 22.03125 4.867188 22.03125 C 4.25 22 4.25 22 4 21.75 C 3.894531 20.65625 3.863281 19.613281 4.25 18.578125 C 5.558594 17.0625 6.8125 16.058594 8.75 15.5 C 8.996094 15.5 9.246094 15.5 9.5 15.5 C 9.082031 14.75 8.671875 14.164062 8.078125 13.53125 C 7.390625 12.605469 7.386719 12.125 7.5 11 C 7.253906 11 7.003906 11 6.75 11 C 6.726562 9.460938 6.726562 9.460938 6.75 9 C 6.832031 8.917969 6.914062 8.835938 7 8.75 C 6.996094 7.941406 6.964844 7.132812 6.9375 6.324219 C 7.027344 5.15625 7.4375 4.566406 8.25 3.746094 C 10.511719 2.082031 12.820312 2.335938 15.5 2.5 Z M 15.5 2.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(19.607843%,76.862746%,50.588238%);fill-opacity:1;" d="M 9.5 15.5 C 9.59375 15.753906 9.6875 16.003906 9.78125 16.265625 C 10.179688 17.210938 10.75 17.886719 11.449219 18.628906 C 11.75 19 11.75 19 11.75 19.5 C 11.914062 19.5 12.078125 19.5 12.25 19.5 C 12.316406 19.355469 12.382812 19.210938 12.453125 19.0625 C 12.855469 18.300781 13.378906 17.671875 13.914062 17 C 14.253906 16.492188 14.390625 16.097656 14.5 15.5 C 16.273438 15.738281 17.421875 16.304688 18.75 17.5 C 18.882812 17.609375 19.015625 17.722656 19.152344 17.835938 C 19.738281 18.402344 19.988281 18.894531 20.085938 19.695312 C 20.09375 20.390625 20.082031 21.058594 20 21.75 C 19.554688 22.195312 18.902344 22.03125 18.304688 22.03125 C 17.992188 22.03125 17.683594 22.035156 17.363281 22.035156 C 17.023438 22.035156 16.683594 22.035156 16.34375 22.03125 C 15.996094 22.035156 15.652344 22.035156 15.304688 22.035156 C 14.578125 22.035156 13.851562 22.035156 13.121094 22.03125 C 12.1875 22.03125 11.253906 22.03125 10.320312 22.035156 C 9.605469 22.035156 8.890625 22.035156 8.171875 22.035156 C 7.828125 22.035156 7.484375 22.035156 7.140625 22.035156 C 6.660156 22.035156 6.179688 22.035156 5.695312 22.03125 C 5.285156 22.03125 5.285156 22.03125 4.867188 22.03125 C 4.25 22 4.25 22 4 21.75 C 3.894531 20.65625 3.863281 19.613281 4.25 18.578125 C 5.65625 16.953125 7.230469 15.5 9.5 15.5 Z M 9.5 15.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(74.509805%,38.039216%,8.235294%);fill-opacity:1;" d="M 15.5 2.5 C 15.5625 2.722656 15.625 2.945312 15.6875 3.171875 C 15.949219 4.011719 15.949219 4.011719 16.523438 4.609375 C 17.148438 5.453125 17.117188 5.992188 17.09375 7.03125 C 17.089844 7.335938 17.085938 7.640625 17.085938 7.953125 C 17 8.75 17 8.75 16.5 9.5 C 16.433594 9.292969 16.367188 9.085938 16.296875 8.871094 C 16.210938 8.597656 16.121094 8.328125 16.03125 8.046875 C 15.945312 7.777344 15.855469 7.507812 15.765625 7.230469 C 15.519531 6.464844 15.519531 6.464844 15 5.75 C 14.296875 5.742188 13.648438 5.773438 12.953125 5.84375 C 11.578125 5.972656 10.363281 6 9 5.75 C 8.382812 6.492188 8.085938 7.136719 7.828125 8.0625 C 7.734375 8.398438 7.734375 8.398438 7.636719 8.738281 C 7.59375 8.90625 7.546875 9.074219 7.5 9.25 C 6.808594 8.558594 6.925781 8.132812 6.921875 7.171875 C 6.917969 6.890625 6.914062 6.605469 6.910156 6.316406 C 7.039062 5.164062 7.445312 4.558594 8.25 3.746094 C 10.511719 2.082031 12.820312 2.335938 15.5 2.5 Z M 15.5 2.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(84.705883%,63.921571%,14.509805%);fill-opacity:1;" d="M 9.5 14.5 C 9.792969 14.628906 10.085938 14.757812 10.390625 14.890625 C 11.871094 15.445312 13.085938 15.144531 14.5 14.5 C 14.640625 15.808594 14.605469 16.5 13.796875 17.5625 C 13.535156 17.878906 13.269531 18.191406 13 18.5 C 12.871094 18.683594 12.742188 18.867188 12.605469 19.058594 C 12.488281 19.203125 12.371094 19.351562 12.25 19.5 C 12.085938 19.5 11.921875 19.5 11.75 19.5 C 11.394531 19.105469 11.058594 18.699219 10.734375 18.28125 C 10.554688 18.054688 10.378906 17.828125 10.195312 17.59375 C 9.382812 16.507812 9.480469 15.828125 9.5 14.5 Z M 9.5 14.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(16.470589%,65.098041%,41.176471%);fill-opacity:1;" d="M 9 15.5 C 9.164062 15.5 9.328125 15.5 9.5 15.5 C 9.59375 15.753906 9.6875 16.003906 9.78125 16.265625 C 10.179688 17.210938 10.75 17.886719 11.449219 18.628906 C 11.75 19 11.75 19 11.75 19.5 C 11.914062 19.5 12.078125 19.5 12.25 19.5 C 12.316406 19.355469 12.382812 19.210938 12.453125 19.0625 C 12.855469 18.300781 13.378906 17.671875 13.914062 17 C 14.253906 16.492188 14.390625 16.097656 14.5 15.5 C 14.664062 15.5 14.828125 15.5 15 15.5 C 14.960938 17.316406 14.011719 18.488281 12.75 19.75 C 12.691406 20.128906 12.648438 20.507812 12.609375 20.890625 C 12.589844 21.097656 12.566406 21.304688 12.546875 21.515625 C 12.53125 21.675781 12.515625 21.835938 12.5 22 C 12.171875 22 11.839844 22 11.5 22 C 11.503906 21.859375 11.503906 21.722656 11.507812 21.578125 C 11.445312 20.035156 11.011719 19.289062 9.921875 18.207031 C 9.289062 17.519531 9.011719 17 8.96875 16.0625 C 8.984375 15.785156 8.984375 15.785156 9 15.5 Z M 9 15.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(70.980394%,31.37255%,8.235294%);fill-opacity:1;" d="M 15.25 3.75 C 16.527344 4.390625 16.527344 4.390625 17 5.25 C 17.089844 6.085938 17.089844 6.085938 17.078125 7.015625 C 17.078125 7.324219 17.078125 7.628906 17.074219 7.945312 C 17 8.75 17 8.75 16.5 9.5 C 16.402344 9.1875 16.402344 9.1875 16.296875 8.871094 C 16.210938 8.597656 16.121094 8.328125 16.03125 8.046875 C 15.945312 7.777344 15.855469 7.507812 15.765625 7.230469 C 15.519531 6.464844 15.519531 6.464844 15 5.75 C 14.359375 5.566406 14.359375 5.566406 13.75 5.5 C 14.246094 4.921875 14.738281 4.34375 15.25 3.75 Z M 15.25 3.75 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(97.254902%,97.254902%,94.117647%);fill-opacity:1;" d="M 9.5 16 C 9.707031 16.125 9.914062 16.246094 10.125 16.375 C 11.144531 16.8125 11.90625 16.917969 13 16.75 C 13.851562 16.410156 13.851562 16.410156 14.5 16 C 14.164062 16.945312 13.742188 17.664062 13.125 18.453125 C 12.972656 18.652344 12.820312 18.847656 12.664062 19.050781 C 12.527344 19.199219 12.390625 19.347656 12.25 19.5 C 12.085938 19.5 11.921875 19.5 11.75 19.5 C 11.273438 18.988281 11.273438 18.988281 10.734375 18.3125 C 10.554688 18.09375 10.378906 17.871094 10.195312 17.644531 C 9.792969 17.0625 9.605469 16.6875 9.5 16 Z M 9.5 16 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(85.490197%,63.137257%,10.980392%);fill-opacity:1;" d="M 7.5 8.5 C 7.808594 9.394531 7.6875 10.085938 7.5 11 C 7.253906 11 7.003906 11 6.75 11 C 6.75 10.339844 6.75 9.679688 6.75 9 C 6.914062 9 7.078125 9 7.25 9 C 7.332031 8.835938 7.414062 8.671875 7.5 8.5 Z M 7.5 8.5 "/>--}}
{{--                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(85.882354%,64.313728%,11.372549%);fill-opacity:1;" d="M 16.25 9 C 16.578125 9 16.910156 9 17.25 9 C 17.25 9.660156 17.25 10.320312 17.25 11 C 17.003906 11 16.753906 11 16.5 11 C 16.417969 10.339844 16.335938 9.679688 16.25 9 Z M 16.25 9 "/>--}}
{{--                            </g>--}}
{{--                        </svg>--}}
{{--                        </span>--}}
{{--                        <i class="fas fa-chevron-down ml-[0.5] text-xs transition-transform duration-200"--}}
{{--                           :class="{ 'rotate-180': open }"></i>--}}
{{--                    </button>--}}

{{--                    <!-- Dropdown Menu -->--}}
{{--                    <div--}}
{{--                        x-show="open"--}}
{{--                        @click.away="open = false"--}}
{{--                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50"--}}
{{--                        x-cloak--}}
{{--                    >--}}
{{--                        <!-- Dropdown Items -->--}}
{{--                        <ul class="py-2">--}}
{{--                            <!-- Dashboard -->--}}
{{--                            <li>--}}
{{--                                <a href="{{ route(Auth::user()->role == 'super' ? 'super.dashboard' : (Auth::user()->role == 'admin' ? 'admin.dashboard' : (Auth::user()->role == 'user' ? 'dashboard' : 'login'))) }}"--}}
{{--                                   class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fa-regular fa-rectangle-list mr-2"></i> Dashboard--}}
{{--                                </a>--}}
{{--                            </li>--}}


{{--                            <!-- Profile -->--}}
{{--                            <li>--}}
{{--                                <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fas fa-user-circle mr-2"></i> Profile--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <!-- Orders -->--}}
{{--                            <li>--}}
{{--                                <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fas fa-shopping-bag mr-2"></i> Orders--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <!-- Wishlist -->--}}
{{--                            <li>--}}
{{--                                <a href="/wishlist" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fas fa-heart mr-2"></i> Wishlist--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <!-- Settings -->--}}
{{--                            <li>--}}
{{--                                <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fas fa-cog mr-2"></i> Settings--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <!-- Change Password -->--}}
{{--                            <li>--}}
{{--                                <a href="{{ route('password.change.form') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                    <i class="fa-solid fa-key mr-2"></i> Change Password--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <!-- Divider -->--}}
{{--                            <li class="border-t border-gray-200"></li>--}}

{{--                            <!-- Logout -->--}}
{{--                            <li>--}}
{{--                                <form method="POST" action="{{ route('logout') }}">--}}
{{--                                    @csrf--}}
{{--                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition">--}}
{{--                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout--}}
{{--                                    </button>--}}
{{--                                </form>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}

{{--                    <!-- Tooltip -->--}}
{{--                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity truncate">--}}
{{--                        {{ Auth::user()->name }}--}}
{{--                    </span>--}}
{{--                @else--}}
{{--                    <!-- Login Link (for guest users) -->--}}
{{--                    <a href="{{ route('login') }}" class="text-white hover:text-lime-500 relative focus:outline-none">--}}
{{--                        <i class="fas fa-user"></i>--}}
{{--                    </a>--}}
{{--                    <!-- Tooltip -->--}}
{{--                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-black bg-lime-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">--}}
{{--                        Account--}}
{{--                    </span>--}}
{{--                @endauth--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Pull-Down Search Box (Mobile Only) -->--}}
{{--    <div class="lg:hidden hidden mt-2 px-4 relative" id="mobile-search-box">--}}
{{--        <form class="w-full flex items-center relative" role="search">--}}
{{--            <input type="search" name="q" class="w-full px-4 py-2 bg-gray-50 text-gray-900 rounded-full border-1 border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-16" autocomplete="off" placeholder="Enter Your Keyword..." aria-label="Search products" />--}}
{{--            <button class="absolute right-0 top-0 bottom-0 bg-lime-500 text-white px-6 rounded-r-full flex items-center justify-center" type="submit" aria-label="Search">--}}
{{--                <i class="fas fa-search"></i>--}}
{{--            </button>--}}
{{--        </form>--}}
{{--        <!-- Mobile Search Results Dropdown -->--}}
{{--        <div id="mobile-search-dropdown" class="absolute top-full left-0 right-0 mx-4 max-w-full bg-white shadow-md rounded-lg -mt-1 hidden z-50 border border-gray-200 max-h-auto overflow-y-auto">--}}
{{--            <ul id="mobile-search-results" class="py-2 text-gray-900"></ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</nav>--}}

{{--<!-- Cart Sidebar -->--}}
{{--<div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[80%] md:w-96 bg-white/90 backdrop-blur-xl shadow-2xl transform transition-transform duration-500 ease-in-out translate-x-full z-50 rounded-l-2xl">--}}
{{--    <!-- Header -->--}}
{{--    <div class="p-5 border-b bg-gradient-to-r from-gray-950 to-gray-800 text-white rounded-tl-2xl">--}}
{{--        <div class="flex justify-between items-center">--}}
{{--            <h2 class="text-xl font-semibold flex items-center gap-2">--}}
{{--                <i class="fas fa-shopping-cart"></i> Cart (0)--}}
{{--            </h2>--}}
{{--            <button id="close-cart-sidebar" class="text-white hover:text-gray-300 transition">--}}
{{--                <i class="fas fa-times text-lg"></i>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Cart Items -->--}}
{{--    <div class="p-5 space-y-6 flex flex-col h-full">--}}
{{--        <div id="cart-items" class="space-y-4 max-h-[300px] overflow-y-auto flex-grow">--}}
{{--            <div class="text-center text-gray-600">Your cart is empty! <br> Explore Our Products</div>--}}
{{--        </div>--}}

{{--        <!-- Subtotal & Checkout -->--}}
{{--        <div class="py-4 border-t">--}}
{{--            <div class="flex justify-between text-lg font-semibold text-gray-900">--}}
{{--                <span>Subtotal:</span>--}}
{{--                <span>Tk 0</span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Fixed Bottom Buttons -->--}}
{{--    <div class="fixed bottom-0 w-full md:w-96 max-w-full bg-white p-4 border-t">--}}
{{--        <a href="https://www.tarpor.com/cart" class="block w-full bg-gray-950 text-white hover:text-black font-medium py-3 text-center rounded-lg hover:bg-lime-500 transition">--}}
{{--            View Cart--}}
{{--        </a>--}}
{{--    </div>--}}
{{--</div>--}}
