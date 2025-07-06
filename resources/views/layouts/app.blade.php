<!DOCTYPE html>
<html lang="en" dir="ltr" prefix="og: http://ogp.me/ns#">
<head>
    @include('components.app.meta')
    <title>
        @if(request()->is('/'))
            Tarpor - Premium Fashion for Kids & Men in Bangladesh
        @else
            {{ config('app.name') . ' - ' }}@yield('title', 'Dashboard')
        @endif
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Urbanist'] bg-[var(--light)] text-gray-900 flex flex-col min-h-screen pt-16">
<main id="main-content" class="flex-grow">
<div id="cookie-consent" class="fixed inset-x-0 bottom-0 bg-white shadow-lg z-50 transform transition-transform duration-300 translate-y-full">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-gray-700 text-sm md:text-base">
                    We use cookies to enhance your experience on our website. By continuing to browse, you agree to our use of cookies.
                    <a href="/privacy-policy" class="text-[var(--primary)] font-medium hover:underline">Learn more</a>
                </p>
            </div>
            <div class="flex gap-3">
                <button id="accept-cookies" class="bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary-dark)] transition-colors duration-200 text-sm md:text-base font-medium">
                    Accept All
                </button>
                <button id="reject-cookies" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-50 transition-colors duration-200 text-sm md:text-base font-medium">
                    Reject Non-Essential
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Skip Link -->
<a href="#main-content" class="sr-only focus:not-sr-only bg-[var(--primary)] text-white px-4 py-2 absolute top-0 left-0 z-50 transition-transform duration-200 transform focus:translate-y-2">
    Skip to main content
</a>

<!-- Navigation -->
@include('components.app.nav')
@include('components.app.off-canvas-menu')
@include('components.app.search-modal')
@include('components.app.mobile-nav')

<!-- Main Content -->
<main id="main-content">
    @yield('content')
</main>

<!-- Footer -->
@include('partials.app.footer')

<!-- Back to Top -->
@include('components.app.back-to-top')

<!-- Quick View Modal -->
@include('components.app.quick-view-modal')

<!-- Sticky CTA -->
@include('components.app.sticky-cta')

<!-- Toast Notification  -->
@include('components.app.toast')


@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('scripts')
</body>
</html>
