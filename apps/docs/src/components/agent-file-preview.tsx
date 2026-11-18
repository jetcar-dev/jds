import {CodeBlock, Pre} from 'fumadocs-ui/components/codeblock';
import {readAgentsMarkdown, readDesignMarkdown, readSkillMarkdown} from '@/lib/agent-files';

const files = {
  'AGENTS.md': readAgentsMarkdown,
  'DESIGN.md': readDesignMarkdown,
  'SKILL.md': readSkillMarkdown,
};

export async function AgentFilePreview({name, collapsible}: {name: keyof typeof files; collapsible?: boolean}) {
  const content = await files[name]();
  if (!content) return null;

  const preview = (
    <CodeBlock title={name} className="docs-code-card">
      <Pre>{content}</Pre>
    </CodeBlock>
  );

  if (!collapsible) return preview;

  return (
    <details className="docs-code-details">
      <summary>{name} 미리보기</summary>
      {preview}
    </details>
  );
}
