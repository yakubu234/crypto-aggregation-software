import { defineConfig } from 'vite';
import * as laravel from 'laravel-vite-plugin';
import path from 'path';


export default defineConfig({
    plugins: [
        laravel.default({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
});
