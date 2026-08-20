import type {Metadata} from 'next'
import type {ReactNode} from 'react'
import {RootProvider} from 'fumadocs-ui/provider/next'
import './globals.css'

export const metadata: Metadata = {
    title: {
        default: 'JDS',
        template: '%s · JDS',
    },
    description: 'JDS 컴포넌트 문서',
    icons: {
        icon: '/jds-logo.svg',
    },
}

export default function RootLayout({children}: {children: ReactNode}) {
    return (
        <html lang="ko" suppressHydrationWarning>
            <body>
                <RootProvider>{children}</RootProvider>
            </body>
        </html>
    )
}
