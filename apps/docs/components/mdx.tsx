import defaultMdxComponents from 'fumadocs-ui/mdx'
import * as TabsComponents from 'fumadocs-ui/components/tabs'
import type {MDXComponents} from 'mdx/types'
import {BladePreview} from './blade-preview'
import {IconGallery} from './icon-gallery'

export function getMDXComponents(components?: MDXComponents) {
    return {
        ...defaultMdxComponents,
        ...TabsComponents,
        BladePreview,
        IconGallery,
        ...components,
    } satisfies MDXComponents
}

export const useMDXComponents = getMDXComponents

declare global {
    type MDXProvidedComponents = ReturnType<typeof getMDXComponents>
}
