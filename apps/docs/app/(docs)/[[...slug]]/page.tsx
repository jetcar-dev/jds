import type {Metadata} from 'next'
import {notFound} from 'next/navigation'
import {
    DocsBody,
    DocsDescription,
    DocsPage,
    DocsTitle,
} from 'fumadocs-ui/layouts/docs/page'
import {ComponentApi} from '@/components/component-api'
import {getMDXComponents} from '@/components/mdx'
import {source} from '@/lib/source'

type PageProps = {
    params: Promise<{slug?: string[]}>
}

export default async function Page({params}: PageProps) {
    const {slug} = await params
    const page = source.getPage(slug)
    if (!page) notFound()

    const MDX = page.data.body
    const componentSlug = slug?.[0] === 'components' ? slug.at(-1) : undefined
    const toc = componentSlug
        ? [...page.data.toc, {title: 'API 안내', url: '#api-reference', depth: 2}]
        : page.data.toc

    return (
        <DocsPage toc={toc}>
            <DocsTitle>{page.data.title}</DocsTitle>
            <DocsDescription>{page.data.description}</DocsDescription>
            <DocsBody>
                <MDX components={getMDXComponents()} />
                {componentSlug ? <ComponentApi slug={componentSlug} /> : null}
            </DocsBody>
        </DocsPage>
    )
}

export function generateStaticParams() {
    return source.generateParams()
}

export async function generateMetadata({params}: PageProps): Promise<Metadata> {
    const {slug} = await params
    const page = source.getPage(slug)
    if (!page) notFound()

    return {
        title: page.data.title,
        description: page.data.description,
    }
}
