import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // Use ASSET_URL if provided (e.g., https://example.com or https://example.com/subfolder)
    base: process.env.ASSET_URL ?? '/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
