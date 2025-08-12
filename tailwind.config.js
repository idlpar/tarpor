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
                primary: '#2C5F2D', // Deep Forest Green
                'primary-dark': '#1F4020', // Darker Forest Green
                secondary: '#F5F5DC', // Cream/Beige
                accent: '#B8860B', // Dark Goldenrod
                'text-dark': '#333333', // Charcoal
                'text-light': '#666666', // Medium Gray
                'bg-light': '#F8F8F8', // Very Light Gray
                'input-bg': '#FFFFFF', // White
                'input-border': '#D1D5DB', // Light Gray
                error: '#DC2626', // Red
                success: '#16A34A', // Green
                rose: {
                    50: '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#f43f5e',
                    600: '#e11d48',
                    700: '#be123c',
                    800: '#9f1239',
                    900: '#881337',
                    950: '#4c051e',
                },
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
    plugins: [require('@tailwindcss/typography')],
};
