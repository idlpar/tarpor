<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description"
      content="{{ $metaDescription ?? 'Tarpor offers premium fashion for kids and men in Bangladesh, blending cultural heritage with modern trends. Shop vibrant collections today!' }}">
<meta name="keywords"
      content="{{ $metaKeywords ?? 'kids fashion, men\'s fashion, Bangladesh clothing, festive wear, ethnic wear, Tarpor, traditional clothing, premium fashion' }}">
<meta name="author" content="Tarpor">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#A68A64">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Tags -->
<meta property="og:title" content="{{ $ogTitle ?? 'Tarpor - Premium Fashion for Kids & Men in Bangladesh' }}">
<meta property="og:description"
      content="{{ $ogDescription ?? 'Discover stylish and comfortable clothing for kids and men at Tarpor. Shop now for festive and everyday wear!' }}">
<meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Tarpor">
<meta property="og:locale" content="en_US">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $twitterTitle ?? 'Tarpor - Premium Fashion for Kids & Men in Bangladesh' }}">
<meta name="twitter:description"
      content="{{ $twitterDescription ?? 'Discover stylish and comfortable clothing for kids and men at Tarpor. Shop now for festive and everyday wear!' }}">
<meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">
<meta name="twitter:site" content="@TarporFashion">

<!-- Structured Data (JSON-LD) -->
<script type="application/ld+json">
    @php
        echo json_encode([
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Tarpor",
            "url" => url()->current(),
            "logo" => asset('images/logo.png'),
            "sameAs" => [
                "https://www.facebook.com/tarpor",
                "https://www.instagram.com/tarpor",
                "https://www.twitter.com/tarpor"
            ],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => "+880-123-456-7890",
                "contactType" => "Customer Service",
                "email" => "support@tarpor.com",
                "availableLanguage" => ["English", "Bengali"]
            ]
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    @endphp
</script>

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('logos/favicon.ico') }}">
@if (file_exists(public_path('images/apple-touch-icon.png')))
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
@endif

<!-- Preload Logo -->
<link rel="preload" href="{{ asset('logos/logo.svg') }}" as="image" type="image/svg+xml"/>

<!-- Preload External Styles -->
<link rel="preload"
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Urbanist:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700&display=swap"
      as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" as="style"
      onload="this.rel='stylesheet'">
<noscript>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Urbanist:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
</noscript>

<!-- Vite Assets (Tailwind and Vite-managed JS) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Manual CSS (loads last to override other styles) -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<link rel="preload" href="{{ asset('css/app.css') }}" as="style" onload="this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</noscript>
<meta name="csrf-token" content="{{ csrf_token() }}">
@stack('styles')
