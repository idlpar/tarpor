import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss(), // No config here (uses tailwind.config.js)
                autoprefixer(),
            ],
        },
    },
    build: {
        cssCodeSplit: true, // Split CSS for faster loading of critical styles
        minify: 'esbuild', // Minify CSS and JS for production
        rollupOptions: {
            output: {
                manualChunks: {
                    // Optional: Split vendor and app code for better caching
                    vendor: ['tailwindcss'],
                },
            },
        },
    },
});
