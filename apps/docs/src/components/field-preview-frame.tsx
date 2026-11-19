'use client';

import {useEffect, useRef, useState, type ComponentPropsWithoutRef} from 'react';

export function FieldPreviewFrame(props: ComponentPropsWithoutRef<'iframe'>) {
  const frame = useRef<HTMLIFrameElement>(null);
  const [height, setHeight] = useState<number>();

  useEffect(() => {
    function resize(event: MessageEvent) {
      if (event.source !== frame.current?.contentWindow || event.data?.type !== 'jds:field-preview-height') return;
      const nextHeight = event.data.height;
      if (Number.isInteger(nextHeight) && nextHeight >= 220 && nextHeight <= 1800) setHeight(nextHeight);
    }

    window.addEventListener('message', resize);
    return () => window.removeEventListener('message', resize);
  }, []);

  return <iframe {...props} ref={frame} style={height ? {height} : undefined} />;
}
