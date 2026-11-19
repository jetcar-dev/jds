import {readFile} from 'node:fs/promises';
import {resolve} from 'node:path';
import {bladeExamples, readBladeExample, type BladeExampleComponent} from '@/lib/blade-examples';
import type {source} from './source';

type DocPage = NonNullable<ReturnType<typeof source.getPage>>;

export async function renderMarkdown(page: DocPage) {
  const raw = await readFile(resolve(process.cwd(), 'content/docs', page.path), 'utf8');
  let body = raw
    .replace(/^---\r?\n[\s\S]*?\r?\n---\r?\n/, '')
    .replace(/^<AgentFilePreview name="(?:AGENTS|DESIGN|SKILL)\.md"(?: collapsible)? \/>\r?\n(?:\r?\n)?/gm, '')
    .trim();

  const component = (Object.keys(bladeExamples) as BladeExampleComponent[])
    .find(name => {
      const examples = bladeExamples[name];
      const slug = 'docSlug' in examples ? examples.docSlug : name;
      return page.path === `components/${slug}.mdx`;
    });
  if (component) {
    const {tag, names} = bladeExamples[component];
    const examples = await Promise.all(names.map(async (name) => [name, await readBladeExample(component, name)] as const));
    for (const [name, blade] of examples) {
      body = body.replace(new RegExp(`^<${tag} name="${name}" label="[^"]+" />$`, 'm'), () => (
        `\`\`\`blade\n${blade.trim()}\n\`\`\``
      ));
    }
  }
  return [`# ${page.data.title}`, page.data.description, body].filter(Boolean).join('\n\n');
}

export function markdownResponse(content: string) {
  return new Response(content + '\n', {headers: {'Content-Type': 'text/markdown; charset=utf-8'}});
}
