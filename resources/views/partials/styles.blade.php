<style>
    :root {
        --primary: #E63946;
        --primary-dark: #C1121F;
        --secondary: #F1FAEE;
        --dark: #1D3557;
        --light: #F8F9FA;
        --gold: #D4AF37;
        --nav-bg: #112233;
        --emerald: #064E3B;
        --burgundy: #4A2C2A;
        --ivory: #FAF3E0;
    }

    /* Base Styles */
    body {
        font-family: 'Urbanist', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
    }

    .font-brand {
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 700;
    }

    .font-bengali {
        font-family: 'Noto Serif Bengali', serif;
    }

    /* Navigation Styles */
    .nav-container {
        backdrop-filter: blur(12px);
        background: rgba(17, 34, 51, 0.95);
        transition: all 0.4s cubic-bezier(0.65, 0, 0.35, 1);
    }

    .nav-link {
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-link:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }

    .nav-link:hover:after,
    .nav-link.active:after {
        width: 100%;
    }

    /* Card Styles */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        will-change: transform;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .category-card:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Modal Styles */
    .modal, .search-modal {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .search-modal input:focus {
        box-shadow: 0 0 15px rgba(230, 57, 70, 0.3);
    }

    /* Parallax Effect */
    .parallax-bg {
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
    }

    @media (prefers-reduced-motion: reduce) {
        .parallax-bg {
            background-attachment: scroll;
        }
    }

    /* Button Styles */
    .btn-primary {
        transition: all 0.3s ease;
        transform: perspective(1px) translateZ(0);
        will-change: transform;
    }

    .btn-primary:hover {
        transform: perspective(1px) translateZ(0) scale(1.05);
        box-shadow: 0 10px 20px rgba(230, 57, 70, 0.3);
    }

    /* Preloader */
    #preloader {
        position: fixed;
        inset: 0;
        background: var(--light);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.5s ease;
    }

    /* Animations */
    .animate-text {
        animation: slideIn 1s ease-in-out;
    }

    @keyframes slideIn {
        0% { transform: translateY(20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    /* Scrollbar Styles */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--secondary);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }

    /* Focus Styles for Accessibility */
    :focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #print-content, #print-content * {
            visibility: visible;
        }
        #print-content {
            position: absolute;
            left: 0;
            top: 0;
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 640px) {
        .hero-video {
            object-fit: cover;
            height: 100%;
        }

        .search-modal {
            max-width: 90%;
            padding: 1.5rem;
        }

        .quick-view-modal .modal {
            max-width: 90%;
            padding: 1.5rem;
        }

        .product-card img {
            height: 16rem;
        }

        .category-card img {
            height: 20rem;
        }

        /* Adjust font sizes for mobile */
        h1 {
            font-size: 2rem;
        }

        h2 {
            font-size: 1.75rem;
        }

        /* Simplify navigation for mobile */
        .nav-container {
            padding: 0.75rem 1rem;
        }
    }

    /* Reduced motion preferences */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
