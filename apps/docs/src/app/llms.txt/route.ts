import {markdownResponse} from '@/lib/markdown';
import {source} from '@/lib/source';

export function GET(request: Request) {
  const pages = source.getPages().map(({url, data}) =>
    `- [${data.title}](${new URL(url, request.url)}): ${data.description ?? ''}`.trimEnd(),
  );
  return markdownResponse([
    '# JDS',
    '> JDS v3 문서',
    '## 문서',
    ...pages,
    `- [전체 문서](${new URL('/llms-full.txt', request.url)})`,
    `- [디자인 기준](${new URL('/DESIGN.md', request.url)})`,
    `- [저장소 작업 지침](${new URL('/AGENTS.md', request.url)})`,
  ].join('\n\n'));
}
