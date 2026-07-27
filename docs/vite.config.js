import {defineConfig} from 'vite'
import laravel from 'laravel-vite-plugin'
import {fileURLToPath, URL} from 'node:url'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // JDS Blade 컴포넌트 수정은 전체 화면 새로고침으로 반영
            refresh: [
                'resources/views/**',
                '../package/resources/views/**',
            ],
        }),
    ],
    server: {
        // docs 밖의 JDS 원본도 Vite가 감시해 수정 즉시 화면에 반영
        fs: {
            allow: [
                fileURLToPath(new URL('.', import.meta.url)),
                fileURLToPath(new URL('../package', import.meta.url)),
            ],
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
})
