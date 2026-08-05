import {defineConfig} from '@playwright/test'

export default defineConfig({
    testDir: './tests/Browser',
    timeout: 15_000,
    expect: {timeout: 5_000},
    fullyParallel: false,
    retries: process.env.CI ? 1 : 0,
    reporter: process.env.CI ? 'github' : 'list',
    snapshotPathTemplate: '{testDir}/__snapshots__/{arg}{ext}',
    use: {
        baseURL: 'http://127.0.0.1:8137',
        locale: 'ko-KR',
        timezoneId: 'Asia/Seoul',
        reducedMotion: 'reduce',
        viewport: {width: 1280, height: 900},
    },
    projects: [
        {name: 'chrome', use: {channel: 'chrome'}},
        {name: 'edge', use: {channel: 'msedge'}},
    ],
    webServer: {
        command: 'php artisan serve --host=127.0.0.1 --port=8137',
        url: 'http://127.0.0.1:8137/up',
        reuseExistingServer: !process.env.CI,
        timeout: 60_000,
    },
})
