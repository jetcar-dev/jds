import {highlight} from 'fumadocs-core/highlight';
import {CodeBlock, Pre} from 'fumadocs-ui/components/codeblock';
import {
  readBladeExample,
  readButtonPlayground,
  readPreviewAssets,
  renderBladeExample,
  type BladeExampleComponent,
  type BladeExampleName,
} from '@/lib/blade-examples';

type ExampleProps<Component extends BladeExampleComponent> = {
  name: BladeExampleName<Component>;
  label: string;
};

async function BladeExample<Component extends BladeExampleComponent>({
  component, name, label,
}: ExampleProps<Component> & {component: Component}) {
  const hasPlayground = component === 'button' && name === 'usage';
  const [blade, html, {font, theme, styles, script}, playground] = await Promise.all([
    readBladeExample(component, name),
    renderBladeExample(component, name),
    readPreviewAssets(component),
    hasPlayground ? readButtonPlayground() : '',
  ]);
  const code = await highlight(blade.trim(), {
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
      @layer jds-theme, jds-components;
      @layer jds-theme {
        ${font.replace('./PretendardVariable.woff2', '/fonts/PretendardVariable.woff2')}
        ${theme}
      }
      @layer jds-components { ${styles} }
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
      body[data-example="full-width"],
      body[data-example="social"] {
        display: grid;
        grid-template-columns: minmax(0, min(100%, var(--demo-width)));
        justify-items: stretch;
      }
      body[data-example="full-width"] { --demo-width: 400px; }
      body[data-example="social"] { --demo-width: 20rem; }
    </style>
    <script>
      const colorMode = matchMedia('(prefers-color-scheme: dark)');
      const syncTheme = () => {
        document.documentElement.dataset.theme = colorMode.matches ? 'dark' : 'light';
      };
      colorMode.addEventListener('change', syncTheme);
      syncTheme();
    </script>
    <body data-example="${name}" ${hasPlayground ? 'data-playground' : ''}>
      ${hasPlayground ? `<div class="demo-actions">${html}</div>` : html}
      ${playground}${script ? `<script>${script}</script>` : ''}
    </body>
    </html>`;

  return (
    <div className="jds-demo-card not-prose my-6">
      <iframe
        className={name === 'usage' ? 'jds-demo-stage jds-demo-stage--usage' : 'jds-demo-stage'}
        data-example={`${component}.${name}`}
        loading={name === 'usage' ? 'eager' : 'lazy'}
        sandbox="allow-scripts"
        srcDoc={srcDoc}
        title={label}
      />
      <div className="jds-demo-code" id={`${component}-code-${name}`}>
        <CodeBlock data-line-numbers className="jds-demo-code-block" viewportProps={{'aria-label': 'Blade 코드'}}>
          {code}
        </CodeBlock>
      </div>
      <details className="jds-demo-code-toggle">
        <summary aria-controls={`${component}-code-${name}`}>
          <span className="jds-demo-code-more">코드 보기</span>
          <span className="jds-demo-code-less">코드 접기</span>
        </summary>
      </details>
    </div>
  );
}

export function ButtonExample(props: ExampleProps<'button'>) {
  return <BladeExample component="button" {...props} />;
}

export function SeparatorExample(props: ExampleProps<'separator'>) {
  return <BladeExample component="separator" {...props} />;
}
