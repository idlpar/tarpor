import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#A68A64', // Muted gold
                'primary-dark': '#8B6F47', // Darker gold
                secondary: '#F5E6CC', // Soft cream
                dark: '#1A1C2B', // Deep charcoal
                light: '#F9F6EE', // Off-white
                gold: '#D4A017', // Vibrant gold
                'nav-bg': '#1A1C2B', // Navigation background
                emerald: '#355E3B', // Rich green
                burgundy: '#5C2C2A', // Deep burgundy
                ivory: '#F3E9DC', // Warm ivory
                'red-close': '#D32F2F', // Red for close buttons
                'red-badge': '#EF4444', // Red for notification badges
            },
            fontFamily: {
                brand: ['Playfair Display', 'serif', ...defaultTheme.fontFamily.serif],
                bengali: ['Noto Serif Bengali', 'serif', ...defaultTheme.fontFamily.serif],
                sans: ['Urbanist', ...defaultTheme.fontFamily.sans], // Default font from HTML
            },
            // Optional: Add custom animations or other theme settings
            animation: {
                slideIn: 'slideIn 1s ease-in-out',
                pulse: 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                slideIn: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                pulse: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.1)' },
                },
            },
        },
    },
    plugins: [],
};
