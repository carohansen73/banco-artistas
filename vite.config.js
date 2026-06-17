import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/app-publico.css',
                'resources/css/galery.css',
                'resources/js/app.js',
                'resources/js/admin-sidebar.js',
                'resources/js/admin-artistas-visibility.js',
                'resources/js/admin-artista-show.js',
                'resources/js/admin-usuarios.js',
                'resources/js/filter-artists.js',
                'resources/js/artist-inscription-form.js',
                'resources/js/artist-media-manager.js',
                'resources/js/artist-edit.js',
                'resources/js/galery-lightbox.js',
                'resources/js/preview-img.js',
            ],
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
