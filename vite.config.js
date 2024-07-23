import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import eslint from 'vite-plugin-eslint';
import {copy} from "vite-plugin-copy";

/* if you're using React */
// import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        /* react(), // if you're using React */
        symfonyPlugin(),
        eslint({
            cache: false,
            fix: true,
        }),
        copy({
            targets: [
                {src: 'assets/*', dest: 'dist/assets'}
            ],
            hook: 'writeBundle',
        }),
    ],
    build: {
        outDir: 'public/build',
        rollupOptions: {
            input: 'script/main.js',
        },
        assetsDir: 'assets',
    },
    publicDir: 'public',
    root: './',
    server: {
        port: 3001,
    },
});
