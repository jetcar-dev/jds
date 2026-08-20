'use client'

import {useEffect, useMemo, useState} from 'react'
import {DynamicCodeBlock} from 'fumadocs-ui/components/dynamic-codeblock'
import {Tab, Tabs} from 'fumadocs-ui/components/tabs'

type BladePreviewProps = {
    component: string
    name: string
    code: string
}

export function BladePreview({component, name, code}: BladePreviewProps) {
    const [theme, setTheme] = useState<'light' | 'dark'>('light')
    const [height, setHeight] = useState(180)
    const previewId = `${component}/${name}`
    const src = useMemo(() => {
        const query = new URLSearchParams({theme})
        return `/_blade-preview/${encodeURIComponent(component)}/${encodeURIComponent(name)}?${query}`
    }, [component, name, theme])

    useEffect(() => {
        const root = document.documentElement
        const syncTheme = () => setTheme(root.classList.contains('dark') ? 'dark' : 'light')
        const observer = new MutationObserver(syncTheme)

        syncTheme()
        observer.observe(root, {attributes: true, attributeFilter: ['class', 'style']})
        return () => observer.disconnect()
    }, [])

    useEffect(() => {
        const receiveHeight = (event: MessageEvent) => {
            if (event.data?.source !== 'jds-blade-preview' || event.data?.id !== previewId) return
            setHeight(Math.max(120, Number(event.data.height) || 180))
        }

        window.addEventListener('message', receiveHeight)
        return () => window.removeEventListener('message', receiveHeight)
    }, [previewId])

    return (
        <section className="blade-preview" data-example={previewId}>
            <Tabs items={['미리보기', '코드']}>
                <Tab value="미리보기">
                    <iframe
                        className="blade-preview__frame"
                        src={src}
                        title={`${name} 미리보기`}
                        loading="lazy"
                        style={{height}}
                    />
                </Tab>
                <Tab value="코드">
                    <DynamicCodeBlock lang="blade" code={code} />
                </Tab>
            </Tabs>
        </section>
    )
}
