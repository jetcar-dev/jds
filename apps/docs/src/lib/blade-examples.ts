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
  card: {
    tag: 'CardExample',
    names: ['usage', 'variants', 'horizontal', 'with-avatar', 'with-images', 'with-form', 'custom-styles'],
  },
  checkbox: {
    tag: 'CheckboxExample',
    names: [
      'usage', 'variants', 'full-rounded', 'disabled', 'external-label', 'with-description', 'default-selected',
      'invalid', 'controlled', 'indeterminate', 'form', 'render-state', 'custom-element', 'custom-indicator', 'custom-styles',
    ],
  },
  input: {
    tag: 'InputExample',
    names: [
      'usage', 'variants', 'on-surface', 'full-width', 'types', 'text-types', 'date-types', 'control-types', 'action-types',
      'digits', 'letter-masks', 'mixed-masks', 'phone-mask', 'comma-mask', 'controlled', 'controlled-mask', 'custom-styles',
    ],
  },
  label: {
    tag: 'LabelExample',
    names: ['usage', 'custom-styles'],
  },
  separator: {
    tag: 'SeparatorExample',
    names: ['usage', 'variants', 'with-surface', 'vertical', 'with-content', 'decorative', 'custom-element', 'custom-styles'],
  },
  surface: {
    tag: 'SurfaceExample',
    names: ['usage', 'variants', 'with-form-components', 'custom-styles'],
  },
  textarea: {
    tag: 'TextAreaExample',
    docSlug: 'text-area',
    names: ['usage', 'variants', 'on-surface', 'full-width', 'controlled', 'rows', 'custom-styles'],
  },
} as const;
export type BladeExampleComponent = keyof typeof bladeExamples;
export type BladeExampleName<Component extends BladeExampleComponent> = typeof bladeExamples[Component]['names'][number];

export function readBladeExample<Component extends BladeExampleComponent>(component: Component, name: BladeExampleName<Component>) {
  return readFile(resolve(process.cwd(), 'src', 'demos', component, name + '.blade.php'), 'utf8');
}

export async function readPlayground(component: 'button' | 'checkbox' | 'input' | 'textarea') {
  const demos = resolve(process.cwd(), 'src', 'demos');
  const [styles, controls] = await Promise.all([
    readFile(resolve(demos, 'playground.css'), 'utf8'),
    readFile(resolve(demos, component === 'button' ? 'button/playground.html' : 'field-playground.html'), 'utf8'),
  ]);
  return `<style>${styles}</style>${controls}`;
}

const readCommonStyles = cache(() => Promise.all([
  readFile(resolve(root, 'packages/styles/fonts/pretendard.css'), 'utf8'),
  readFile(resolve(root, 'packages/styles/themes/default/variables.css'), 'utf8'),
  readFile(resolve(root, 'packages/styles/base/content.css'), 'utf8'),
]));

export const readPreviewAssets = cache(async (component: BladeExampleComponent) => {
  const components = {
    button: ['button'],
    card: ['card', 'button'],
    checkbox: ['checkbox', 'label', 'button'],
    input: ['field', 'input', 'surface'],
    label: ['field', 'input', 'label'],
    separator: ['separator', 'surface'],
    surface: ['surface', 'field', 'input', 'textarea'],
    textarea: ['field', 'textarea', 'surface'],
  }[component];
  const behavior = {button: 'Button/button.js', checkbox: 'Checkbox/checkbox.js', input: 'Input/input.js'};
  const [[font, theme, base], styles, scripts, previewStyles] = await Promise.all([
    readCommonStyles(),
    Promise.all(components.map(name => readFile(resolve(root, 'packages/styles/components', name + '.css'), 'utf8'))),
    Promise.all(Object.entries(behavior).filter(([name]) => components.includes(name))
      .map(([, file]) => readFile(resolve(root, 'packages/blade/src/Components', file), 'utf8'))),
    component === 'card'
      ? readFile(resolve(process.cwd(), 'src', 'demos', component, 'preview.css'), 'utf8')
      : '',
  ]);
  return {font, theme, base, styles: styles.join('\n'), script: scripts.join('\n'), previewStyles};
});

export async function renderBladeExample<Component extends BladeExampleComponent>(component: Component, name: BladeExampleName<Component>) {
  try {
    const {stdout} = await exec('php', ['blade/render.php', `${component}.${name}`], {cwd: process.cwd()});
    return stdout;
  } catch (error) {
    throw new Error(`${component}.${name} 렌더링 실패. PHP와 문서의 Composer 의존성을 확인하세요. composer install --working-dir=apps/docs/blade`, {cause: error});
  }
}
