import {readFile} from 'node:fs/promises';
import {resolve} from 'node:path';
import {renderMarkdown} from '@/lib/markdown';
import {source} from '@/lib/source';

const repoRoot = resolve(process.cwd(), '..', '..');
const skillRoot = resolve(repoRoot, 'skills', 'jds-blade');

export function readAgentsMarkdown() {
  return readFile(resolve(repoRoot, 'AGENTS.md'), 'utf8');
}

export function readSkillMarkdown() {
  return readFile(resolve(skillRoot, 'SKILL.md'), 'utf8');
}

export async function readDesignMarkdown() {
  const page = source.getPage(['design']);
  return page ? (await renderMarkdown(page)) + '\n' : null;
}

export function agentFileResponse(content: string) {
  return new Response(content, {headers: {'Content-Type': 'text/markdown; charset=utf-8'}});
}
