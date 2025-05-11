{{--<!-- Bottom Navigation (Mobile Only) -->--}}
{{--<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gray-900 text-white shadow-md py-1 z-20">--}}
{{--    <ul class="flex justify-around items-center">--}}
{{--        <li class="text-center">--}}
{{--            <a href="/offers" class="flex flex-col items-center text-white" aria-label="Offers">--}}
{{--                <i class="fas fa-box-open text-sm hover:text-lime-500 transition-colors"></i>--}}
{{--                <span class="text-xs transition-colors">Offers</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="text-center">--}}
{{--            <a href="/all-discount-products" class="flex flex-col items-center text-white" aria-label="Big Sale">--}}
{{--                <i class="fas fa-cart-plus text-sm hover:text-lime-500 transition-colors"></i>--}}
{{--                <span class="text-xs transition-colors">Big Sale</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="text-center">--}}
{{--            <a href="/compare" class="flex flex-col items-center text-white" aria-label="Compare">--}}
{{--                <i class="fas fa-exchange-alt text-sm hover:text-lime-500 transition-colors"></i>--}}
{{--                <span class="text-xs transition-colors">Compare</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="text-center">--}}
{{--                @auth--}}
{{--                    <a href="{{ route(Auth::user()->role == 'super' ? 'super.dashboard' : (Auth::user()->role == 'admin' ? 'admin.dashboard' : (Auth::user()->role == 'user' ? 'dashboard' : 'login'))) }}" class="flex flex-col items-center text-white" aria-label="Account">--}}
{{--                        <i class="fas fa-user-circle text-sm hover:text-lime-500 transition-colors"></i>--}}
{{--                         <span class="text-xs transition-colors truncate">{{Auth::user()->name}}</span>--}}
{{--                    </a>--}}
{{--                @else--}}
{{--                    <a href="{{ route('login') }}" class="flex flex-col items-center text-white" aria-label="Account">--}}
{{--                        <i class="fas fa-user-circle text-sm hover:text-lime-500 transition-colors"></i>--}}
{{--                        <span class="text-xs transition-colors">Account</span>--}}
{{--                    </a>--}}
{{--                @endauth--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</nav>--}}

{{--<!-- Navbar Menu Container -->--}}
{{--<nav class="bg-gray-950 text-white">--}}
{{--    <div class="hidden lg:flex justify-between items-center container mx-auto px-4 pb-2">--}}
{{--        <!-- Desktop Menu -->--}}
{{--        <ul id="desktop-menu" class="hidden lg:flex space-x-6">--}}
{{--            <li class="relative">--}}
{{--                <a href="/home" class="font-bold hover:text-lime-300">--}}
{{--                    <i class="fa fa-home"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="relative group">--}}
{{--                <a href="#" class="lg:font-bold text-sm lg:text-md hover:text-lime-300">Laptop</a>--}}
{{--                <ul class="dropdown absolute left-0 mt-2 w-48 lg:max-h-48 bg-gray-100 text-left text-gray-900 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 overflow-y-auto z-50">--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">Acer</a></li>--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">Apple</a></li>--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">Asus</a></li>--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">Asus</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li class="relative group">--}}
{{--                <a href="#" class="lg:font-bold text-sm lg:text-md hover:text-lime-300">Desktop & Server</a>--}}
{{--                <ul class="dropdown absolute left-0 mt-2 w-48 lg:max-h-48 bg-gray-100 text-left text-gray-900 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 overflow-y-auto z-50">--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">All Desktop</a></li>--}}
{{--                    <li><a href="#" class="block lg:px-4 lg:py-2 hover:bg-gray-300">Gaming PC</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li class="relative group">--}}
{{--                <a href="#" class="lg:font-bold text-sm lg:text-md hover:text-lime-300">Software</a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</nav>--}}
