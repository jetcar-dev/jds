import {createMDX} from 'fumadocs-mdx/next'

const previewOrigin = process.env.BLADE_PREVIEW_ORIGIN ?? 'http://127.0.0.1:8001'

/** @type {import('next').NextConfig} */
const config = {
    reactStrictMode: true,
    output: 'standalone',
    allowedDevOrigins: ['127.0.0.1'],
    async rewrites() {
        return [
            {
                source: '/_blade-preview/:path*',
                destination: `${previewOrigin}/_preview/:path*`,
            },
            {
                source: '/_jds/:path*',
                destination: `${previewOrigin}/_jds/:path*`,
            },
        ]
    },
}

export default createMDX()(config)
