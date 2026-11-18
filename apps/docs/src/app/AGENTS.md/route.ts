import {agentFileResponse, readAgentsMarkdown} from '@/lib/agent-files';

export const dynamic = 'force-static';

export async function GET() {
  return agentFileResponse(await readAgentsMarkdown());
}
