import {defineConfig, defineDocs} from 'fumadocs-mdx/config'
import {remarkBladePreview} from './lib/remark-blade-preview'

export const docs = defineDocs({
    dir: './content',
})

export default defineConfig({
    mdxOptions: {
        remarkPlugins: plugins => [remarkBladePreview, ...plugins],
    },
})
