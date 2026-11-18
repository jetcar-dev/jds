import {mkdir, rmdir, unlink, writeFile} from 'node:fs/promises';
import {homedir} from 'node:os';
import {dirname, join} from 'node:path';

const targets = {
  'claude-code': {project: ['.claude', 'skills'], user: ['.claude', 'skills']},
  cursor: {project: ['.cursor', 'skills'], user: ['.cursor', 'skills']},
  opencode: {project: ['.opencode', 'skills'], user: ['.config', 'opencode', 'skills']},
  codex: {project: ['.agents', 'skills'], user: ['.agents', 'skills']},
};

async function install() {
  const args = process.argv.slice(2);
  const agent = args.find(arg => arg.startsWith('--agent='))?.slice('--agent='.length) ?? 'codex';
  const agentArgs = args.filter(arg => arg.startsWith('--agent='));
  if (!Object.hasOwn(targets, agent) || agentArgs.length > 1 ||
      args.some(arg => arg !== '--project' && !arg.startsWith('--agent='))) {
    throw new Error('Usage: node jds-skill-install.mjs [--project] [--agent=codex|claude-code|cursor|opencode]');
  }

  const project = args.includes('--project');
  const [base, segments] = project
    ? [process.cwd(), targets[agent].project]
    : agent === 'opencode' && process.env.XDG_CONFIG_HOME
      ? [process.env.XDG_CONFIG_HOME, ['opencode', 'skills']]
      : [homedir(), targets[agent].user];

  const destination = join(base, ...segments, 'jds-blade');
  await mkdir(dirname(destination), {recursive: true});
  try {
    await mkdir(destination);
  } catch (error) {
    if (error.code === 'EEXIST') {
      throw new Error(`jds-blade already exists at ${destination}. Use a different scope or update the existing skill separately.`);
    }
    throw error;
  }

  const scripts = join(destination, 'scripts');
  try {
    await mkdir(scripts);
    await writeFile(join(destination, 'SKILL.md'), skillFiles.markdown, {flag: 'wx'});
    await writeFile(join(scripts, 'inspect-heroui.mjs'), skillFiles.inspector, {flag: 'wx'});
  } catch (error) {
    await unlink(join(scripts, 'inspect-heroui.mjs')).catch(() => {});
    await unlink(join(destination, 'SKILL.md')).catch(() => {});
    await rmdir(scripts).catch(() => {});
    await rmdir(destination).catch(() => {});
    throw error;
  }

  process.stdout.write(`Installed jds-blade to ${destination}\n`);
}

try {
  await install();
} catch (error) {
  process.stderr.write(error.message + '\n');
  process.exitCode = 1;
}
