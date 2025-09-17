import { defineConfig } from 'vite';
import path from 'path';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',                 'resources/js/app.js'
            , 'resources/js/wishlist.js', 'resources/js/cookie-consent.js', 'resources/css/print.css'],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tinymce/skins/*',
                    dest: 'tinymce/skins'
                },
                {
                    src: 'node_modules/tinymce/icons/default/*',
                    dest: 'tinymce/icons/default'
                },
                {
                    src: 'node_modules/tinymce/plugins/autoresize/*',
                    dest: 'tinymce/plugins/autoresize'
                },
                {
                    src: 'node_modules/tinymce/themes/silver/*',
                    dest: 'tinymce/themes/silver'
                }
            ]
        })
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
