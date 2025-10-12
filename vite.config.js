import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        exclude: [
            'react_jsx-dev-runtime',
            'chunk-KMU3Z7QX',
            'chunk-G3PMV62Z'
        ]
    }
});
