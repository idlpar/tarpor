@php
    $menuItems = [
     [
         'title' => 'Dashboard',
         'icon' => 'tachometer-alt',
         'route' => '#',
         'submenu' => []
     ],
     [
         'title' => 'Profile',
         'icon' => 'user',
         'route' => '#',
         'submenu' => [
             [
                 'title' => 'View Profile',
                 'icon' => 'eye',
                 'route' => '#'
             ],
             [
                 'title' => 'Edit Profile',
                 'icon' => 'edit',
                 'route' => '#'
             ]
         ]
     ],
     [
         'title' => 'Settings',
         'icon' => 'cog',
         'route' => '#',
         'submenu' => [
             [
                 'title' => 'Account Settings',
                 'icon' => 'user-cog',
                 'route' => '#'
             ],
             [
                 'title' => 'Privacy Settings',
                 'icon' => 'shield-alt',
                 'route' => '#'
             ]
         ]
     ],
     [
         'title' => 'Messages',
         'icon' => 'envelope',
         'route' => '#',
         'submenu' => []
     ],
     [
         'title' => 'Notifications',
         'icon' => 'bell',
         'route' => '#',
         'submenu' => []
     ],
     [
         'title' => 'Orders',
         'icon' => 'shopping-cart',
         'route' => '#',
         'submenu' => []
     ]
 ];
@endphp
<div class="fixed md:relative w-64 bg-gray-300 shadow-lg h-screen lg:h-auto transition-all duration-300 transform"
     @mouseenter="isSidebarCollapsed = false"
     @mouseleave="isSidebarCollapsed = true; openSubmenu = null"
     :class="{ '-translate-x-full md:translate-x-0 md:w-16': isSidebarCollapsed, 'translate-x-0': !isSidebarCollapsed }">
    <!-- Toggle Button Inside Left Nav -->
    <div class="flex justify-between items-center">
        <!-- Sidebar Content -->
        <div class="p-4">
            <h2 class="text-xl font-bold transition-opacity duration-300 ease-in-out" :class="{ 'hidden': isSidebarCollapsed }">
                <span class="capitalize">{{ Auth::user()->role }}</span>&nbsp;Dashboard
            </h2>
        </div>

        <button @click="isSidebarCollapsed = !isSidebarCollapsed; if (isSidebarCollapsed) { openSubmenu = null }"
                x-show="!isSidebarCollapsed"
                x-transition
                class="p-2 rounded-lg hover:bg-amber-100 mx-2">
            <x-sidebar.sidebar-toggle-left-icon />
        </button>
    </div>

    <ul class="space-y-2 p-2">
        <!-- Dynamically Generate Menu Items -->
        @foreach ($menuItems as $item)
            @if (isset($item['logout']) && $item['logout'])
                <!-- Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg group relative"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                    <span :class="{ 'opacity-0 translate-x-[-10px] w-0 overflow-hidden': isSidebarCollapsed, 'opacity-100 translate-x-0': !isSidebarCollapsed }" class="ml-4 lg:ml-2 transition-all duration-300 ease-in-out opacity-0 translate-x-[-10px] w-0 overflow-hidden">
                                {{ $item['title'] }}
                            </span>
                    <!-- Tooltip -->
                    <span x-show="isSidebarCollapsed" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50">
                                {{ $item['title'] }}
                            </span>
                </a>
            @elseif  (empty($item['submenu']))
                <!-- Menu Item Without Submenu -->
                <li>
                    <a href="{{ $item['route'] }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg group relative">
                        <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                        <span :class="{ 'opacity-0 translate-x-[-10px]': isSidebarCollapsed, 'opacity-100 translate-x-0': !isSidebarCollapsed }"
                              class="transition-all duration-300 ease-in-out">
                                    {{ $item['title'] }}
                                </span>
                        <!-- Tooltip for collapsed state -->
                        <span x-show="isSidebarCollapsed"
                              class="absolute left-16 bg-gray-700 text-white text-sm px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    {{ $item['title'] }}
                                </span>
                    </a>
                </li>
            @else
                <!-- Menu Item With Submenu -->
                <li>
                    <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg cursor-pointer group relative"
                         @click="openSubmenu === '{{ strtolower($item['title']) }}' ? (openSubmenu = null, isSidebarCollapsed = false) : (openSubmenu = '{{ strtolower($item['title']) }}', isSidebarCollapsed = false)">
                        <i class="fas fa-{{ $item['icon'] }} mr-2"></i>
                        <span :class="{ 'opacity-0 translate-x-[-10px] w-0 overflow-hidden': isSidebarCollapsed, 'opacity-100 translate-x-0': !isSidebarCollapsed }"
                              class="transition-all duration-300 ease-in-out">
                                    {{ $item['title'] }}
                                </span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-200"
                           :class="{ 'rotate-180': openSubmenu === '{{ strtolower($item['title']) }}', 'hidden': isSidebarCollapsed }"></i>
                        <!-- Tooltip for collapsed state -->
                        <span x-show="isSidebarCollapsed"
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
                                <a href="{{ $subItem['route'] }}" class="flex items-center p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-{{ $subItem['icon'] }} mr-2"></i>
                                    <span :class="{ 'opacity-0 translate-x-[-10px]': isSidebarCollapsed, 'opacity-100 translate-x-0': !isSidebarCollapsed }"
                                          class="transition-all duration-300 ease-in-out">
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
