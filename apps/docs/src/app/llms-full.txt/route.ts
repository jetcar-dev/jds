import {markdownResponse, renderMarkdown} from '@/lib/markdown';
import {source} from '@/lib/source';

export const dynamic = 'force-static';

export async function GET() {
  const pages = await Promise.all(source.getPages().map(renderMarkdown));
  return markdownResponse(pages.join('\n\n---\n\n'));
}
