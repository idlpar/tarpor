import { defineConfig } from 'vite';
import path from 'path';
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
                tailwindcss(),
                autoprefixer(),
            ],
        },
    },
    build: {
        cssCodeSplit: true,
        minify: 'esbuild',
        rollupOptions: {
            output: {
                
            },
        },
    },
    resolve: {
        alias: {
            '@blowstack/ckeditor5-full-free-build': path.resolve(__dirname, 'node_modules/@blowstack/ckeditor5-full-free-build/build/ckeditor.js'),
        },
    },
});
