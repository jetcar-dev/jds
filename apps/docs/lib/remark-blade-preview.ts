import path from 'node:path'
import {visit} from 'unist-util-visit'

type CodeNode = {
    type: 'code'
    lang?: string | null
    meta?: string | null
    value: string
}

type ParentNode = {
    children: unknown[]
}

export function remarkBladePreview() {
    return (tree: unknown, file: {path?: string}) => {
        const component = file.path
            ? path.basename(file.path, path.extname(file.path))
            : 'example'

        visit(tree as never, 'code', (node: CodeNode, index: number | undefined, parent: ParentNode | undefined) => {
            if (node.lang !== 'blade' || !node.meta?.includes('preview') || index === undefined || !parent) {
                return
            }

            const name = node.meta.match(/name="([^"]+)"/)?.[1] ?? `${component}-example-${index + 1}`

            parent.children[index] = {
                type: 'mdxJsxFlowElement',
                name: 'BladePreview',
                attributes: [
                    {type: 'mdxJsxAttribute', name: 'component', value: component},
                    {type: 'mdxJsxAttribute', name: 'name', value: name},
                    {type: 'mdxJsxAttribute', name: 'code', value: node.value},
                ],
                children: [],
            }
        })
    }
}
