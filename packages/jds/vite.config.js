import {defineConfig} from 'vite'

export default defineConfig({
    base: './',
    publicDir: false,
    build: {
        emptyOutDir: true,
        manifest: true,
        outDir: 'public/dist',
        rollupOptions: {
            input: 'resources/js/jds.js',
            output: {
                entryFileNames: 'jds.js',
                assetFileNames: 'jds.[ext]',
            },
        },
    },
})
