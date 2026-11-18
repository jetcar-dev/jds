import {agentFileResponse, readDesignMarkdown} from '@/lib/agent-files';

export const dynamic = 'force-static';

export async function GET() {
  const design = await readDesignMarkdown();
  if (!design) return new Response(null, {status: 404});
  return agentFileResponse(design);
}
