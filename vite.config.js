import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'; // Assurez-vous que 'path' est importé

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/chatRoom/notif.js',
                'resources/js/chatRoom/affiche_user.js',
                'resources/js/chatRoom/btn_image.js',

                'resources/js/bootstrap.js'

            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // Ajoutez cet alias
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
});
