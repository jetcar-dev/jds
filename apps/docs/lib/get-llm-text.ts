import {source} from '@/lib/source'

export async function getLLMText(page: (typeof source)['$inferPage']) {
    const content = await page.data.getText('processed')

    return `# ${page.data.title} (${page.url})\n\n${content}`
}
