import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                ...refreshPaths,
                'app/Filament/**',
                'app/Forms/Components/**',
                'app/Livewire/**',
                'app/Infolists/Components/**',
                'app/Providers/Filament/**',
                'app/Tables/Columns/**',
            ],
        }),
    ],
    server: {
        host: '0.0.0.0', 
        hmr: {
            host: 'localhost',
            port: 5173,      // Puerto explícito para evitar confusiones
            protocol: 'ws',  // Protocolo WebSocket
        },
        watch: {
            usePolling: true, // Crucial para que WSL detecte cambios en los archivos de Filament
        },
    }
})
