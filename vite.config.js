import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/css/app-publico.css',
                'resources/js/app.js',
                'resources/js/filter-artists.js'],
            refresh: true,
        }),
    ],
     server: {
        host: '0.0.0.0',        // escucha en todas las interfaces
        cors: true,              // permite cualquier origen
        hmr: {
            host: 'banco-artistas.local',  // le dice al cliente dónde conectarse
        },
    },
});
