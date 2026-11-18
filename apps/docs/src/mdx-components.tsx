import defaultComponents from 'fumadocs-ui/mdx';
import {AgentFilePreview} from '@/components/agent-file-preview';
import {ButtonExample, SeparatorExample} from '@/components/blade-example';

const DefaultCodeBlock = defaultComponents.pre;

export function getMDXComponents() {
  return {
    ...defaultComponents,
    AgentFilePreview,
    ButtonExample,
    SeparatorExample,
    pre: (props: Parameters<typeof DefaultCodeBlock>[0]) => (
      <DefaultCodeBlock
        {...props}
        className={['docs-code-card', props.className].filter(Boolean).join(' ')}
      />
    ),
  };
}
