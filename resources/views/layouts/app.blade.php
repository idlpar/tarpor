<!DOCTYPE html>
<html lang="en" dir="ltr" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('components.app.meta')
    <title>
        @hasSection('title')
            @yield('title') | {{ config('app.name') }}
        @else
            {{ config('app.name') }} - Premium Fashion for Kids & Men in Bangladesh
        @endif
    </title>

{{--    <!-- Meta Pixel Code -->--}}
{{--    <script>--}}
{{--      !function(f,b,e,v,n,t,s)--}}
{{--      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?--}}
{{--      n.callMethod.apply(n,arguments):n.queue.push(arguments)};--}}
{{--      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';--}}
{{--      n.queue=[];t=b.createElement(e);t.async=!0;--}}
{{--      t.src=v;s=b.getElementsByTagName(e)[0];--}}
{{--      s.parentNode.insertBefore(t,s)}(window, document,'script',--}}
{{--      'https://connect.facebook.net/en_US/fbevents.js');--}}
{{--      fbq('init', '########'); // Replace with your Pixel ID--}}
{{--      fbq('track', 'PageView');--}}
{{--    </script>--}}
{{--    <noscript><img height="1" width="1" style="display:none"--}}
{{--      src="https://www.facebook.com/tr?id=########&ev=PageView&noscript=1"--}}
{{--    /></noscript>--}}
{{--    <!-- End Meta Pixel Code -->--}}

{{--    <!-- Google tag (gtag.js) -->--}}
{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=########"></script>--}}
{{--    <script>--}}
{{--      window.dataLayer = window.dataLayer || [];--}}
{{--      function gtag(){dataLayer.push(arguments);}--}}
{{--      gtag('js', new Date());--}}
{{--      gtag('config', '########'); // Replace with your Measurement ID (G-XXXXXXXXXX) or Conversion ID (AW-XXXXXXXXX)--}}
{{--    </script>--}}
{{--    <!-- End Google Tag -->--}}

{{--    <!-- TikTok Pixel Code -->--}}
{{--    <script>--}}
{{--      !function (w, d, t) {--}}
{{--        w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","load","push","start","on","off","once","ready","alias","group","enableCookie","disableCookie","bind","untrack","setAndDefer","init","trackBase","trackEvent","trackAdEvent"];ttq.setAndDefer=function(e,n){e[n]=function(){e.push([n].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e].push({tid:e,options:n});ttq.version="1.1";ttq.queue=[];var a=d.createElement(t);a.setAttribute("type","text/javascript");a.setAttribute("async",!0);a.setAttribute("src",i+"?sdkid="+e+"&lib="+t);var o=d.getElementsByTagName(t)[0];o.parentNode.insertBefore(a,o)};--}}
{{--        ttq.load('########'); // Replace with your Pixel ID--}}
{{--        ttq.page();--}}
{{--      }(window, document, 'tiktok');--}}
{{--    </script>--}}
{{--    <!-- End TikTok Pixel Code -->--}}

    </head>
<body class="font-['Urbanist'] bg-[var(--light)] text-gray-900 flex flex-col min-h-screen pt-16">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    .swal2-popup-custom {
        border-radius: 1rem; /* Rounded corners */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    }
    .swal2-title-custom {
        color: #1f2937; /* Dark gray text */
        font-weight: 700; /* Bold */
    }
    .swal2-html-container-custom {
        color: #4b5563; /* Medium gray text */
    }

    /* Responsive SweetAlert2 styles */
    @media (max-width: 768px) {
        .swal2-popup {
            width: 90% !important; /* Make it wider on small screens */
            margin: 1em auto !important; /* Center it with some margin */
            top: 20px !important; /* Position it near the top */
            transform: translate(-50%, 0) !important; /* Adjust for centering */
            left: 50% !important;
        }
        .swal2-container {
            align-items: flex-start !important; /* Align to top */
        }
    }
</style>
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
<main id="main-content" class="flex-grow">
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


@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/wishlist.js', 'resources/js/cookie-consent.js'])
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tippy('[data-tippy-content]', {
            allowHTML: true,
        });
    });
</script>
</body>
</html>
