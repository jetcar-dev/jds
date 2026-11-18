import {DocsLayout} from 'fumadocs-ui/layouts/notebook';
import type {ReactNode} from 'react';
import {source} from '@/lib/source';

export default function Layout({children}: {children: ReactNode}) {
  return (
    <DocsLayout
      tree={source.getPageTree()}
      nav={{title: 'JDS', url: '/docs', mode: 'top'}}
      sidebar={{collapsible: false}}
    >
      {children}
    </DocsLayout>
  );
}
