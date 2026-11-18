import {agentFileResponse, readSkillMarkdown} from '@/lib/agent-files';

export const dynamic = 'force-static';

export async function GET() {
  return agentFileResponse(await readSkillMarkdown());
}
