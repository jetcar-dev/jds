import {readFile, readdir} from 'node:fs/promises';
import {join, resolve} from 'node:path';

const [checkout, command, component, ...extra] = process.argv.slice(2);
const slugCommands = ['docs', 'guide', 'source', 'styles'];
const usage = 'Usage: node scripts/inspect-heroui.mjs <heroui-checkout> <list|theme|docs|guide|source|styles> [slug]';

async function printFiles(paths) {
  for (const path of paths) {
    const content = await readFile(path, 'utf8');
    process.stdout.write(`--- ${path} ---\n${content.trimEnd()}\n`);
  }
}

async function documentation(root, section) {
  const path = join(root, 'apps', 'docs', 'content', 'docs', 'en', 'react', section);
  const entries = await readdir(path, {recursive: true, withFileTypes: true});
  return entries.filter(entry => entry.isFile() && entry.name.endsWith('.mdx'));
}

async function inspect(root) {
  switch (command) {
    case 'list': {
      const entries = await documentation(root, 'components');
      const slugs = entries.map(({name}) => name.slice(0, -4));
      process.stdout.write([...new Set(slugs)].sort().join('\n') + '\n');
      break;
    }
    case 'docs':
    case 'guide': {
      const entries = await documentation(root, command === 'docs' ? 'components' : 'getting-started');
      const matches = entries.filter(({name}) => name === component + '.mdx');
      if (matches.length === 0) throw new Error(`Documentation was not found for "${component}".`);
      await printFiles(matches.map(entry => join(entry.parentPath, entry.name)));
      break;
    }
    case 'source':
      await printFiles([join(root, 'packages', 'react', 'src', 'components', component, component + '.tsx')]);
      break;
    case 'styles':
      await printFiles([
        join(root, 'packages', 'styles', 'components', component + '.css'),
        join(root, 'packages', 'styles', 'src', 'components', component, component + '.styles.ts'),
      ]);
      break;
    case 'theme':
      await printFiles([
        join(root, 'packages', 'styles', 'themes', 'default', 'variables.css'),
        join(root, 'packages', 'styles', 'themes', 'shared', 'theme.css'),
      ]);
      break;
  }
}

const validCommand = slugCommands.includes(command)
  ? /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(component ?? '')
  : ['list', 'theme'].includes(command) && component === undefined;

if (!checkout || !validCommand || extra.length > 0) {
  process.stderr.write(usage + '\n');
  process.exitCode = 1;
} else {
  try {
    await inspect(resolve(checkout));
  } catch (error) {
    process.stderr.write(error.message + '\n');
    process.exitCode = 1;
  }
}
