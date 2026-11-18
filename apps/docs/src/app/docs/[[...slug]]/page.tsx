import {DocsBody, DocsDescription, DocsPage, DocsTitle} from 'fumadocs-ui/layouts/notebook/page';
import {notFound} from 'next/navigation';
import {source} from '@/lib/source';
import {getMDXComponents} from '@/mdx-components';

type Props = {params: Promise<{slug?: string[]}>};

export function generateStaticParams() {
  return source.generateParams();
}

export async function generateMetadata({params}: Props) {
  const page = source.getPage((await params).slug);
  if (!page) notFound();
  return {title: page.data.title, description: page.data.description};
}

export default async function Page({params}: Props) {
  const page = source.getPage((await params).slug);
  if (!page) notFound();

  const Body = page.data.body;
  return (
    <DocsPage toc={page.data.toc}>
      <DocsTitle>{page.data.title}</DocsTitle>
      <DocsDescription>{page.data.description}</DocsDescription>
      <DocsBody><Body components={getMDXComponents()} /></DocsBody>
    </DocsPage>
  );
}
