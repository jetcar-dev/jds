'use client'

import {useEffect, useMemo, useState} from 'react'

export function IconGallery() {
    const [theme, setTheme] = useState<'light' | 'dark'>('light')
    const src = useMemo(() => `/_blade-icons?theme=${theme}`, [theme])

    useEffect(() => {
        const root = document.documentElement
        const syncTheme = () => setTheme(root.classList.contains('dark') ? 'dark' : 'light')
        const observer = new MutationObserver(syncTheme)

        syncTheme()
        observer.observe(root, {attributes: true, attributeFilter: ['class', 'style']})
        return () => observer.disconnect()
    }, [])

    return (
        <iframe
            className="icon-gallery-frame"
            src={src}
            title="JDS 전체 아이콘 목록"
            loading="lazy"
        />
    )
}
