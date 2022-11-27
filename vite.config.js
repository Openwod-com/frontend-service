import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Configures the web browser to use localhost, but vite will still run under 0.0.0.0 because using sail.
    server: {
        hmr: { host: 'localhost' },
    },
});
