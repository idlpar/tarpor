@props([
    'links' => [],
    'title' => '',
    'showHome' => true
])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center space-x-1 text-sm">
        @if ($showHome)
            <!-- Home / Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center text-emerald-600 hover:text-emerald-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
        @endif

        <!-- Breadcrumb Links -->
        @foreach ($links as $label => $url)
            <li class="flex items-center">
                <!-- Chevron -->
                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>

                @if ($url)
                    <a href="{{ $url }}" class="ml-1 text-emerald-600 hover:text-emerald-700 transition">
                        {{ $label }}
                    </a>
                @else
                    <span class="ml-1 text-gray-500">{{ $label }}</span>
                @endif
            </li>
        @endforeach

        <!-- Current Page Title -->
        @if ($title)
            <li class="flex items-center">
                <!-- Chevron -->
                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>

                <span class="ml-1 font-semibold text-emerald-700">{{ $title }}</span>
            </li>
        @endif
    </ol>
</nav>
