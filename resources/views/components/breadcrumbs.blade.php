@push('styles')
    <style>
        /* Stroke Gradient */
        .stroke-gradient {
            stroke-width: 1;
            fill: none; /* Only the stroke should have the gradient */
            stroke: url(#gradientStroke); /* Apply the linear gradient */
        }

        /* Flowing Gradient Animation */
        @keyframes strokeGradientFlow {
            0% { stroke-dashoffset: 100; }
            50% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: -100; }
        }

        .animate-stroke {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: strokeGradientFlow 3s infinite linear;
        }
    </style>
@endpush
@props(['links' => [], 'title' => null])

<div class="flex flex-col md:flex-row items-start md:items-center justify-between md:mx-4 mb-2 md:mb-6 space-y-3 md:space-y-0">
    <!-- Left Side: Toggle Button and Title -->
    <div class="flex justify-between items-center">
        <!-- Toggle Button -->
        <button @click="$store.sidebar.isCollapsed = false"
                x-show="$store.sidebar.isCollapsed"
                x-transition
                class="hidden md:block md:px-6 rounded-lg text-dark hover:text-white hover:bg-lime-800 mr-1 md:mr-4">
                <x-icon name="icon-menu-left"
                        class="rotate-180 h-8 w-8 -mb-2 stroke-gradient animate-stroke"
                />
                <!-- Global SVG Gradient Definition (Place in layout or once in the page) -->
                <svg width="0" height="0">
                    <defs>
                        <linearGradient id="gradientStroke" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6366F1"/>  <!-- Indigo -->
                            <stop offset="20%" stop-color="#8B5CF6"/> <!-- Purple -->
                            <stop offset="40%" stop-color="#EC4899"/> <!-- Pink -->
                            <stop offset="60%" stop-color="#F97316"/> <!-- Orange -->
                            <stop offset="80%" stop-color="#EAB308"/> <!-- Yellow -->
                            <stop offset="100%" stop-color="#84CC16"/> <!-- Lime -->
                        </linearGradient>
                    </defs>
                </svg>
{{--            <x-sidebar.sidebar-toogle-right-icon />--}}
        </button>

        <!-- Title -->
        <h1 class="text-lg lg:text-3xl font-bold text-gray-800">{{ $title }}</h1>
    </div>

    <!-- Right Side: Breadcrumb -->
    <nav class="flex w-full md:w-auto ml-auto" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            @foreach($links as $label => $url)
                <li class="flex items-center">
                    @if(!$loop->first)
                        <!-- Separator (Chevron) -->
                        <svg class="w-4 h-4 text-gray-400 mx-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    @endif

                    @if(!$loop->last)
                        <!-- Link for non-last items -->
                        <a href="{{ $url }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">
                            {{ $label }}
                        </a>
                    @else
                        <!-- Current page (last item) -->
                        <span class="text-sm font-semibold text-gray-700">
                        {{ $label }}
                    </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>
