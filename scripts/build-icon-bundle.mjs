import {mkdir, writeFile} from 'node:fs/promises'
import {dirname, resolve} from 'node:path'
import {fileURLToPath} from 'node:url'

const icons = [
    'material-symbols:add-rounded',
    'solar:bolt-bold',
    'solar:bolt-linear',
    'solar:bookmark-bold',
    'solar:bookmark-linear',
    'solar:box-minimalistic-linear',
    'solar:buildings-bold',
    'solar:buildings-linear',
    'solar:phone-bold',
    'solar:phone-linear',
    'solar:phone-calling-bold',
    'solar:chat-round-dots-bold',
    'solar:chat-round-dots-linear',
    'solar:phone-calling-linear',
    'solar:code-bold',
    'solar:home-angle-2-bold',
    'solar:home-angle-2-linear',
    'solar:map-point-linear',
    'solar:map-point-bold',
    'solar:map-bold',
    'solar:map-linear',
    'solar:maximize-bold',
    'solar:moon-bold',
    'solar:moon-linear',
    'solar:paperclip-bold',
    'solar:pen-bold',
    'solar:paperclip-2-bold',
    'material-symbols:directions-car-rounded',
    'material-symbols:directions-car-outline-rounded',
    'material-symbols:car-crash-rounded',
    'material-symbols:car-crash-outline-rounded',
    'material-symbols:car-gear-rounded',
    'material-symbols:car-gear-outline-rounded',
    'material-symbols:car-tag-rounded',
    'material-symbols:car-tag-outline-rounded',
    'solar:zip-file-bold',
    'solar:zip-file-linear',
]

const grouped = Object.groupBy(icons, icon => icon.split(':', 1)[0])
const bundle = {}

for (const [prefix, namespacedIcons] of Object.entries(grouped)) {
    const names = namespacedIcons.map(icon => icon.slice(prefix.length + 1))
    const response = await fetch(`https://api.iconify.design/${prefix}.json?icons=${encodeURIComponent(names.join(','))}`)

    if (!response.ok) {
        throw new Error(`Iconify ${prefix} 요청 실패: ${response.status}`)
    }

    const data = await response.json()
    const missing = names.filter(name => !data.icons?.[name])

    if (missing.length) {
        throw new Error(`Iconify에 없는 아이콘: ${missing.map(name => `${prefix}:${name}`).join(', ')}`)
    }

    bundle[prefix] = Object.fromEntries(names.map(name => [name, data.icons[name].body]))
}

const output = `// Iconify API 원본에서 생성된 로컬 아이콘 번들입니다.\nexport default ${JSON.stringify(bundle, null, 4)}\n`
const scriptDirectory = dirname(fileURLToPath(import.meta.url))
const outputPath = resolve(scriptDirectory, '../package/resources/js/icons/iconify-extra.js')

await mkdir(dirname(outputPath), {recursive: true})
await writeFile(outputPath, output, 'utf8')
console.log(`${icons.length}개 아이콘 생성: ${outputPath}`)
