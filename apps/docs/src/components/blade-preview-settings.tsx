'use client';

import {useEffect} from 'react';
import {previewMediumViewport, previewSmallViewport} from '@/lib/preview-breakpoints';

export function BladePreviewSettings() {
  useEffect(() => {
    const root = document.documentElement;
    const smallViewport = matchMedia(previewSmallViewport);
    const mediumViewport = matchMedia(previewMediumViewport);

    function syncPreview(frame: HTMLIFrameElement) {
      frame.contentWindow?.postMessage({
        type: 'jds:blade-preview-settings',
        theme: root.classList.contains('dark') ? 'dark' : 'light',
        smallViewport: smallViewport.matches,
        mediumViewport: mediumViewport.matches,
      }, '*');
    }

    function syncPreviews() {
      document.querySelectorAll<HTMLIFrameElement>('iframe.jds-demo-stage').forEach(syncPreview);
    }

    function syncLoadedPreview(event: Event) {
      if (event.target instanceof HTMLIFrameElement && event.target.classList.contains('jds-demo-stage')) {
        syncPreview(event.target);
      }
    }

    const observer = new MutationObserver(syncPreviews);
    observer.observe(root, {attributes: true, attributeFilter: ['class']});
    smallViewport.addEventListener('change', syncPreviews);
    mediumViewport.addEventListener('change', syncPreviews);
    document.addEventListener('load', syncLoadedPreview, true);
    syncPreviews();

    return () => {
      observer.disconnect();
      smallViewport.removeEventListener('change', syncPreviews);
      mediumViewport.removeEventListener('change', syncPreviews);
      document.removeEventListener('load', syncLoadedPreview, true);
    };
  }, []);

  return null;
}
