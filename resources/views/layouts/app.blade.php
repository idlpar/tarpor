<!DOCTYPE html>
<html lang="en" dir="ltr" prefix="og: http://ogp.me/ns#">
<head>
    @include('components.app.meta')
    <title>
        @if(request()->is('/'))
            Tarpor - Premium Fashion for Kids & Men in Bangladesh
        @else
            {{ config('app.name') . ' - ' . (isset($title) ? $title : 'Dashboard') }}
        @endif
    </title>

</head>
<body class="font-['Urbanist'] bg-[var(--light)] text-gray-900">
    <!-- Cookie Consent -->
    <div id="cookie-consent" class="fixed bottom-4 right-4 bg-white p-6 rounded-lg shadow-lg max-w-sm z-50 hidden">
        <p class="text-gray-700 mb-4">We use cookies to enhance your experience. By continuing, you agree to our <a href="#" class="text-[var(--primary)] underline">Privacy Policy</a>.</p>
        <button id="accept-cookies" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg hover:bg-[var(--primary-dark)] transition">Accept</button>
    </div>

    <!-- Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only bg-[var(--primary)] text-white px-4 py-2 absolute top-0 left-0 z-50">Skip to main content</a>

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

    <!-- Manual JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
