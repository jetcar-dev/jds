import {highlight} from 'fumadocs-core/highlight';
import {CodeBlock, Pre} from 'fumadocs-ui/components/codeblock';
import {FieldPreviewFrame} from '@/components/field-preview-frame';
import {
  readBladeExample,
  readPlayground,
  readPreviewAssets,
  renderBladeExample,
  type BladeExampleComponent,
  type BladeExampleName,
} from '@/lib/blade-examples';
import {previewMediumViewport, previewSmallViewport} from '@/lib/preview-breakpoints';

type ExampleProps<Component extends BladeExampleComponent> = {
  name: BladeExampleName<Component>;
  label: string;
};

async function BladeExample<Component extends BladeExampleComponent>({
  component, name, label,
}: ExampleProps<Component> & {component: Component}) {
  const hasFieldPlayground = name === 'usage' && (component === 'checkbox' || component === 'input' || component === 'textarea');
  const hasPlayground = hasFieldPlayground || (name === 'usage' && component === 'button');
  const hasResizablePreview = component === 'textarea' && name === 'rows';
  const hasForm = (component === 'card' && name === 'with-form') || (component === 'input' && name === 'action-types')
    || (component === 'checkbox' && name === 'form');
  const PreviewFrame = hasFieldPlayground || hasResizablePreview ? FieldPreviewFrame : 'iframe';
  const [blade, html, {font, theme, base, styles, script, previewStyles}, playground] = await Promise.all([
    readBladeExample(component, name),
    renderBladeExample(component, name),
    readPreviewAssets(component),
    hasPlayground ? readPlayground(component) : '',
  ]);
  const source = blade.trim();
  const canExpandCode = hasPlayground || source.split('\n').length > 5;
  // Package breakpoints follow the page viewport. Demo layout can still use the iframe width.
  const componentStyles = styles
    .replaceAll(`@media ${previewSmallViewport} {`, ':where(:root[data-preview-sm]) {')
    .replaceAll(`@media ${previewMediumViewport} {`, ':where(:root[data-preview-md]) {');
  const previewHtml = hasPlayground ? `<div class="demo-actions">${html}</div>` : html;
  const resizablePreview = `<div id="resizable-preview" style="display: flex; justify-content: center; width: 100%;">${html}</div>
    <script>
      const content = document.getElementById('resizable-preview');
      new ResizeObserver(() => {
        const bodyStyle = getComputedStyle(document.body);
        const height = Math.ceil(content.getBoundingClientRect().height
          + parseFloat(bodyStyle.paddingBlockStart) + parseFloat(bodyStyle.paddingBlockEnd));
        window.parent.postMessage({type: 'jds:field-preview-height', height}, '*');
      }).observe(content);
    </script>`;
  const code = await highlight(source, {
    lang: 'blade',
    themes: {light: 'github-light', dark: 'github-dark'},
    defaultColor: false,
    components: {pre: Pre},
  });
  const srcDoc = `<!doctype html>
    <html lang="ko">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      @layer jds-theme, jds-base, jds-components;
      @layer jds-theme {
        ${font.replace('./PretendardVariable.woff2', '/fonts/PretendardVariable.woff2')}
        ${theme}
      }
      @layer jds-base { ${base} }
      @layer jds-components { ${componentStyles} }
      body {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-content: center;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        min-height: 100vh;
        margin: 0;
        padding: 16px;
        color: var(--foreground);
        font-family: "Pretendard Variable", "Pretendard", system-ui, sans-serif;
      }
      body:is([data-component="card"], [data-component="surface"]) { line-height: 1.5; }
      @media (width >= 34rem) {
        body:is([data-component="card"], [data-component="surface"]) { padding: 32px; }
      }
      :where(:root[data-preview-sm]) body:is([data-component="input"], [data-component="textarea"]) { padding: 32px; }
      body[data-playground] { display: block; padding: 0; }
      .demo-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px;
      }
      body[data-playground] .demo-actions {
        min-height: 100vh;
        box-sizing: border-box;
        padding: 32px;
      }
      body[data-component="button"]:is([data-example="full-width"], [data-example="social"]) {
        display: grid;
        grid-template-columns: minmax(0, min(100%, var(--demo-width)));
        justify-items: stretch;
      }
      body[data-example="full-width"] { --demo-width: 400px; }
      body[data-example="social"] { --demo-width: 20rem; }
      ${previewStyles}
    </style>
    <script>
      const previewRoot = document.documentElement;
      const colorMode = matchMedia('(prefers-color-scheme: dark)');
      let previewTheme;
      const syncTheme = () => {
        previewRoot.dataset.theme = previewTheme ?? (colorMode.matches ? 'dark' : 'light');
      };
      const syncViewport = (small, medium) => {
        previewRoot.toggleAttribute('data-preview-sm', small);
        previewRoot.toggleAttribute('data-preview-md', medium);
      };
      window.addEventListener('message', (event) => {
        if (event.source !== window.parent || event.data?.type !== 'jds:blade-preview-settings') return;
        const {theme, smallViewport, mediumViewport} = event.data;
        if (theme !== 'dark' && theme !== 'light') return;
        previewTheme = theme;
        syncTheme();
        syncViewport(smallViewport === true, mediumViewport === true);
      });
      colorMode.addEventListener('change', syncTheme);
      syncTheme();
      syncViewport(matchMedia('${previewSmallViewport}').matches, matchMedia('${previewMediumViewport}').matches);
    </script>
    <body data-component="${component}" data-example="${name}" ${hasPlayground ? 'data-playground' : ''}>
      ${hasResizablePreview ? resizablePreview : previewHtml}
      ${playground}${script ? `<script>${script}</script>` : ''}
    </body>
    </html>`;

  return (
    <div className="jds-demo-card not-prose my-6">
      <PreviewFrame
        className={name === 'usage' ? 'jds-demo-stage jds-demo-stage--usage' : 'jds-demo-stage'}
        data-example={`${component}.${name}`}
        loading={name === 'usage' ? 'eager' : 'lazy'}
        sandbox={hasForm ? 'allow-scripts allow-forms' : 'allow-scripts'}
        srcDoc={srcDoc}
        title={label}
      />
      <div className="jds-demo-code" id={`${component}-code-${name}`}>
        <CodeBlock data-line-numbers className="jds-demo-code-block" viewportProps={{'aria-label': 'Blade 코드'}}>
          {code}
        </CodeBlock>
      </div>
      {canExpandCode && (
        <details className="jds-demo-code-toggle">
          <summary aria-controls={`${component}-code-${name}`}>
            <span className="jds-demo-code-more">코드 보기</span>
            <span className="jds-demo-code-less">코드 접기</span>
          </summary>
        </details>
      )}
    </div>
  );
}

export function ButtonExample(props: ExampleProps<'button'>) {
  return <BladeExample component="button" {...props} />;
}

export function CardExample(props: ExampleProps<'card'>) {
  return <BladeExample component="card" {...props} />;
}

export function CheckboxExample(props: ExampleProps<'checkbox'>) {
  return <BladeExample component="checkbox" {...props} />;
}

export function InputExample(props: ExampleProps<'input'>) {
  return <BladeExample component="input" {...props} />;
}

export function LabelExample(props: ExampleProps<'label'>) {
  return <BladeExample component="label" {...props} />;
}

export function SeparatorExample(props: ExampleProps<'separator'>) {
  return <BladeExample component="separator" {...props} />;
}

export function SurfaceExample(props: ExampleProps<'surface'>) {
  return <BladeExample component="surface" {...props} />;
}

export function TextAreaExample(props: ExampleProps<'textarea'>) {
  return <BladeExample component="textarea" {...props} />;
}
