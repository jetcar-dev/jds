import {readFile} from 'node:fs/promises';
import {resolve} from 'node:path';

export const dynamic = 'force-static';

export async function GET() {
  const font = await readFile(resolve(process.cwd(), '../../packages/styles/fonts/PretendardVariable.woff2'));
  return new Response(new Uint8Array(font), {
    headers: {
      'Content-Type': 'font/woff2',
      'Access-Control-Allow-Origin': '*',
      'Cache-Control': 'public, max-age=86400',
    },
  });
}
