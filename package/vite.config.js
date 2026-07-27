import {defineConfig} from 'vite'

export default defineConfig({
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
