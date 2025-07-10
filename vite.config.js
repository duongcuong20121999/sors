import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/index.css',
                'resources/css/management-kiosk.css',
                'resources/css/responsive.css',
                'resources/js/router.js',
                'resources/js/filter-pagination.js',
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
