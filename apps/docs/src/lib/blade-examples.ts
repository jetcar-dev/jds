import {execFile} from 'node:child_process';
import {readFile} from 'node:fs/promises';
import {resolve} from 'node:path';
import {promisify} from 'node:util';
import {cache} from 'react';

const exec = promisify(execFile);
const root = resolve(process.cwd(), '..', '..');

export const bladeExamples = {
  button: {
    tag: 'ButtonExample',
    names: [
      'usage', 'variants', 'sizes', 'with-icons', 'icon-only', 'loading', 'loading-state', 'full-width',
      'disabled', 'social', 'render-function', 'custom-variants', 'ripple-effect', 'custom-styles',
    ],
  },
  separator: {
    tag: 'SeparatorExample',
    names: ['usage', 'variants', 'with-surface', 'vertical', 'with-content', 'decorative', 'custom-element', 'custom-styles'],
  },
} as const;
export type BladeExampleComponent = keyof typeof bladeExamples;
export type BladeExampleName<Component extends BladeExampleComponent> = typeof bladeExamples[Component]['names'][number];

export function readBladeExample<Component extends BladeExampleComponent>(component: Component, name: BladeExampleName<Component>) {
  return readFile(resolve(process.cwd(), 'src', 'demos', component, name + '.blade.php'), 'utf8');
}

export function readButtonPlayground() {
  return readFile(resolve(process.cwd(), 'src', 'demos', 'button', 'playground.html'), 'utf8');
}

const readCommonStyles = cache(() => Promise.all([
  readFile(resolve(root, 'packages/styles/fonts/pretendard.css'), 'utf8'),
  readFile(resolve(root, 'packages/styles/themes/default/variables.css'), 'utf8'),
]));

export const readPreviewAssets = cache(async (component: BladeExampleComponent) => {
  const [[font, theme], styles, script] = await Promise.all([
    readCommonStyles(),
    readFile(resolve(root, 'packages/styles/components', component + '.css'), 'utf8'),
    component === 'button'
      ? readFile(resolve(root, 'packages/blade/src/Components/Button/button.js'), 'utf8')
      : '',
  ]);
  return {font, theme, styles, script};
});

export async function renderBladeExample<Component extends BladeExampleComponent>(component: Component, name: BladeExampleName<Component>) {
  try {
    const {stdout} = await exec('php', ['blade/render.php', `${component}.${name}`], {cwd: process.cwd()});
    return stdout;
  } catch (error) {
    throw new Error(`${component}.${name} 렌더링 실패. PHP와 문서의 Composer 의존성을 확인하세요. composer install --working-dir=apps/docs/blade`, {cause: error});
  }
}
