import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // <-- ESTO ES CLAVE PARA DOCKER
        hmr: {
            host: 'localhost', // <-- ESTO ES CLAVE PARA EL NAVEGADOR
            clientPort: 5173,
        },
        watch: {
            usePolling: true, // A veces necesario en WSL/Docker para detectar cambios
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
