@php
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => route('dashboard'),
            'submenu' => []
        ],
        [
            'title' => 'Products',
            'icon' => 'fas fa-box',
            'route' => '#',
            'submenu' => [
                [
                    'title' => 'All Products',
                    'icon' => 'fas fa-list',
                    'route' => route('products.index')
                ],
                [
                    'title' => 'Add New Product',
                    'icon' => 'fas fa-plus',
                    'route' => route('products.create')
                ]
            ]
        ],
        [
            'title' => 'Categories',
            'icon' => 'fas fa-th-large',
            'route' => '#',
            'submenu' => [
                [
                    'title' => 'All Categories',
                    'icon' => 'fas fa-list',
                    'route' => route('categories.index')
                ],
                [
                    'title' => 'Add New Category',
                    'icon' => 'fas fa-plus',
                    'route' => route('categories.create')
                ]
            ]
        ],
        [
            'title' => 'Orders',
            'icon' => 'fas fa-shopping-cart',
            'route' => route('admin.orders.index'),
            'submenu' => []
        ],
        [
            'title' => 'Customers',
            'icon' => 'fas fa-users',
            'route' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Coupons',
            'icon' => 'fas fa-ticket-alt',
            'route' => '#',
            'submenu' => [
                [
                    'title' => 'All Coupons',
                    'icon' => 'fas fa-list',
                    'route' => route('coupons.index')
                ],
                [
                    'title' => 'Add New Coupon',
                    'icon' => 'fas fa-plus',
                    'route' => route('coupons.create')
                ]
            ]
        ],
        [
            'title' => 'Reports',
            'icon' => 'fas fa-chart-line',
            'route' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Settings',
            'icon' => 'fas fa-cog',
            'route' => '#',
            'submenu' => []
        ],
        [
            'title' => 'Logout',
            'icon' => 'fas fa-sign-out-alt',
            'route' => route('logout'),
            'submenu' => [],
            'logout' => true
        ]
    ];
@endphp

<div class="fixed md:relative bg-gray-900 w-64 shadow-lg h-screen lg:h-auto transition-all duration-300 transform"
     :class="{ '-translate-x-full md:translate-x-0 md:w-16': $store.sidebar.isCollapsed, 'translate-x-0': !$store.sidebar.isCollapsed }"
     x-data="{ openSubmenu: null }"
     x-init="$watch('$store.sidebar.isCollapsed', (value) => { if (value) { openSubmenu = null; } })">

    <!-- Toggle Button Inside Left Nav -->
    <div class="flex justify-between items-center p-2 -mb-2">
        <!-- Admin Dashboard Title -->
        <h2 class="text-xl font-bold transition-all duration-300 ease-in-out"
            :class="{ 'opacity-0 w-0 overflow-hidden': $store.sidebar.isCollapsed, 'opacity-100 w-auto': !$store.sidebar.isCollapsed }">
            <span class="capitalize">admin</span>&nbsp;Dashboard
        </h2>

        <!-- Sidebar Toggle Button -->
        <button @click="$store.sidebar.isCollapsed = !$store.sidebar.isCollapsed"
                x-transition
                class="p-2 rounded-lg text-white hover:bg-lime-700 transition-transform duration-300"
                :class="{ 'rotate-180': $store.sidebar.isCollapsed, '-mr-2': !$store.sidebar.isCollapsed }">
            <x-icon name="menu-left" class="h-8 w-8" />
        </button>
    </div>

    <ul class="space-y-2 p-2">
        @foreach ($menuItems as $item)
            @if (isset($item['logout']) && $item['logout'])
                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="{{ route('logout') }}" class="flex items-center p-2 text-white hover:bg-lime-700 rounded-lg group relative"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                    <span :class="{ 'opacity-0 translate-x-[-10px] w-0 overflow-hidden': $store.sidebar.isCollapsed, 'opacity-100 translate-x-0': !$store.sidebar.isCollapsed }"
                          class="ml-4 lg:ml-2 transition-all duration-300 ease-in-out">
                        {{ $item['title'] }}
                    </span>
                    <span x-show="$store.sidebar.isCollapsed"
                          class="absolute ml-14 px-2 py-1 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50">
                        {{ $item['title'] }}
                    </span>
                </a>
            @elseif  (empty($item['submenu']))
                <!-- Menu Item Without Submenu -->
                <li>
                    <a href="{{ $item['route'] }}" class="flex items-center p-2 text-white hover:bg-lime-700 rounded-lg group relative">
                        <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                        <span :class="{ 'opacity-0 translate-x-[-10px]': $store.sidebar.isCollapsed, 'opacity-100 translate-x-0': !$store.sidebar.isCollapsed }" class="transition-all duration-300 ease-in-out">
                            {{ $item['title'] }}
                        </span>
                        <span x-show="$store.sidebar.isCollapsed"
                              class="absolute left-16 bg-gray-700 text-white text-sm px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            {{ $item['title'] }}
                        </span>
                    </a>
                </li>
            @else
                <!-- Menu Item With Submenu -->
                <li>
                    <div class="flex items-center p-2 text-white hover:bg-lime-700 rounded-lg cursor-pointer group relative"
                         @click="openSubmenu === '{{ strtolower($item['title']) }}' ? (openSubmenu = null, $store.sidebar.isCollapsed = false) : (openSubmenu = '{{ strtolower($item['title']) }}', $store.sidebar.isCollapsed = false)">
                        <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                        <span :class="{ 'opacity-0 translate-x-[-10px] w-0 overflow-hidden': $store.sidebar.isCollapsed, 'opacity-100 translate-x-0': !$store.sidebar.isCollapsed }" class="transition-all duration-300 ease-in-out">
                            {{ $item['title'] }}
                        </span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-200"
                           :class="{ 'rotate-180': openSubmenu === '{{ strtolower($item['title']) }}' }"></i>
                        <span x-show="$store.sidebar.isCollapsed"
                              class="absolute left-16 bg-gray-700 text-white text-sm px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            {{ $item['title'] }}
                        </span>
                    </div>
                    <ul x-show="openSubmenu === '{{ strtolower($item['title']) }}'"
                        x-transition:enter="transition-all ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-6 mt-2">
                        @foreach ($item['submenu'] as $subItem)
                            <li class="mb-2">
                                <a href="{{ $subItem['route'] }}" class="flex items-center p-2 text-white hover:bg-lime-700 rounded-lg">
                                    <i class="fas fa-{{ $subItem['icon'] }} mr-2"></i>
                                    <span :class="{ 'opacity-0 translate-x-[-10px]': $store.sidebar.isCollapsed, 'opacity-100 translate-x-0': !$store.sidebar.isCollapsed }" class="transition-all duration-300 ease-in-out">
                                        {{ $subItem['title'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                isCollapsed: true,
            });
        });
    </script>
@endpush
