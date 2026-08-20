import {defineConfig, defineDocs} from 'fumadocs-mdx/config'
import {remarkBladePreview} from './lib/remark-blade-preview'

export const docs = defineDocs({
    dir: './content',
    docs: {
        postprocess: {
            includeProcessedMarkdown: true,
        },
    },
})

export default defineConfig({
    mdxOptions: {
        remarkPlugins: plugins => [remarkBladePreview, ...plugins],
    },
})
