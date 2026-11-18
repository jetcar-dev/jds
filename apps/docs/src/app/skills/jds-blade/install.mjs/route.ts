import {readFile} from 'node:fs/promises';
import {resolve} from 'node:path';
import {readSkillMarkdown} from '@/lib/agent-files';

export const dynamic = 'force-static';

export async function GET() {
  const [markdown, inspector, installer] = await Promise.all([
    readSkillMarkdown(),
    readFile(resolve(process.cwd(), '../../skills/jds-blade/scripts/inspect-heroui.mjs'), 'utf8'),
    readFile(resolve(process.cwd(), 'scripts/install-skill.mjs'), 'utf8'),
  ]);
  const script = `const skillFiles = ${JSON.stringify({markdown, inspector})};\n${installer}`;
  return new Response(script, {
    headers: {
      'Content-Type': 'text/javascript; charset=utf-8',
      'Content-Disposition': 'attachment; filename="jds-skill-install.mjs"',
    },
  });
}
