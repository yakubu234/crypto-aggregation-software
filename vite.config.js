import { defineConfig } from 'vite';
import * as laravel from 'laravel-vite-plugin';
import path from 'path';


export default defineConfig({
    server: {
        host: '127.0.0.1', // Or 'localhost' / '127.0.0.1'
        port: 5173, // Or your preferred port
        hmr: {
            host: '127.0.0.1', // Or your host if needed
        },
        watch: {
            usePolling: true, // Required for docker
        }
    },
    plugins: [
        laravel.default({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),{
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
});
