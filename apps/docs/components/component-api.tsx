import {existsSync, readFileSync} from 'node:fs'
import path from 'node:path'

type ComponentProp = {
    name: string
    type: string
    defaultValue: string
    description: string
}

type ComponentPart = {
    name: string
    props: ComponentProp[]
    slots: string[]
}

const descriptions: Record<string, string> = {
    avatar: '내부 Avatar에 표시할 이미지 주소입니다.',
    avatarAttributes: '내부 Avatar에 전달할 src, size, color, radius 등의 속성입니다.',
    backdrop: 'Overlay 배경 효과를 지정합니다.',
    checked: '처음 렌더링할 때 선택된 상태로 표시합니다.',
    clearable: '입력값을 지우는 버튼을 표시합니다.',
    closable: '사용자가 닫을 수 있는 버튼을 표시합니다.',
    color: '컴포넌트가 지원하는 의미 색상 중에서 선택합니다.',
    disabled: '사용자 입력과 상호작용을 비활성화합니다.',
    disableAnimation: '움직임 효과를 끄고 상태를 즉시 반영합니다.',
    disabledKeys: '선택할 수 없게 만들 항목의 값을 배열로 지정합니다.',
    errorMessage: 'invalid 상태에서 보여줄 오류 설명입니다.',
    fullWidth: '사용 가능한 가로 너비를 모두 사용합니다.',
    focusable: '키보드 탭 순서에 포함하고 focus-visible 상태를 제공합니다.',
    formatOptions: 'Intl.NumberFormat에 전달할 값 표시 옵션입니다.',
    fillOffset: '단일 값에서 트랙 색상이 채워지기 시작할 기준값입니다.',
    href: '이동할 주소를 지정합니다.',
    iconOnly: '아이콘만 표시하는 정사각형 버튼으로 만듭니다.',
    indeterminate: '정확한 값을 알 수 없거나 일부만 선택된 상태를 표시합니다.',
    hideThumb: '슬라이더 손잡이를 시각적으로 숨깁니다.',
    hideValue: '라벨 옆의 현재 값을 숨깁니다.',
    invalid: '오류 스타일과 aria-invalid 상태를 적용합니다.',
    label: '보이는 라벨과 접근 가능한 이름을 제공합니다.',
    labelPlacement: 'inside, outside, outside-left, outside-top 중에서 라벨 위치를 선택합니다.',
    locale: '값과 날짜를 표시할 언어 및 지역 형식을 지정합니다.',
    loading: '진행 표시를 보여주고 상호작용을 잠급니다.',
    name: '일반 HTML 폼으로 제출할 필드 이름입니다.',
    maxValue: '허용하거나 표시할 최댓값입니다.',
    minValue: '허용하거나 표시할 최솟값입니다.',
    orientation: 'horizontal 또는 vertical 배치 방향입니다.',
    placement: '컴포넌트가 표시될 위치를 지정합니다.',
    placeholder: '값이 없을 때 보여줄 안내 문구입니다.',
    radius: 'none, sm, md, lg, full 중에서 모서리 크기를 선택합니다.',
    readOnly: '현재 값을 보여주되 사용자가 변경하지 못하게 합니다.',
    readonly: '현재 값을 보여주되 사용자가 변경하지 못하게 합니다.',
    required: '필수 입력과 접근성 상태를 적용합니다.',
    selectionMode: 'none, single, multiple 또는 range 선택 규칙입니다.',
    selected: '해당 항목을 처음 선택된 상태로 렌더링합니다.',
    showValueLabel: '포맷된 현재 값을 화면에 표시합니다.',
    showOutline: '슬라이더 손잡이 바깥쪽에 색상 외곽선을 표시합니다.',
    showSteps: 'step 간격마다 트랙에 단계 점을 표시합니다.',
    showTooltip: '손잡이를 가리키거나 조작할 때 현재 값 툴팁을 표시합니다.',
    size: 'sm, md, lg 중에서 크기를 선택합니다.',
    striped: '진행 표시 영역에 색상에 맞는 줄무늬를 적용합니다.',
    marks: '트랙에 표시할 값과 라벨 목록입니다.',
    tooltipValueFormatOptions: '슬라이더 툴팁 값에만 적용할 Intl.NumberFormat 옵션입니다.',
    disableThumbScale: '손잡이를 드래그할 때 안쪽 원이 축소되는 효과를 끕니다.',
    value: '초기값 또는 현재 값입니다.',
    valueLabel: '계산된 값 대신 표시할 고정 문구입니다.',
    variant: '컴포넌트가 지원하는 시각적 형태를 선택합니다.',
    classNames: '컴포넌트의 각 data-slot에 추가할 클래스 목록입니다.',
}

const partDescriptions: Record<string, Record<string, string>> = {
    user: {
        name: 'Avatar 대체 텍스트와 화면에 표시할 사용자 이름입니다.',
        description: '이름 아래에 표시할 문자열 또는 description named slot입니다.',
    },
}

function kebab(value: string) {
    return value.replace(/([a-z0-9])([A-Z])/g, '$1-$2').replace(/_/g, '-').toLowerCase()
}

function findPackageRoot() {
    const candidates = [
        path.resolve(process.cwd(), '../../packages/jds'),
        path.resolve(process.cwd(), 'packages/jds'),
    ]

    return candidates.find((candidate) => existsSync(candidate))
}

function readBalancedProps(source: string) {
    const marker = source.indexOf('@props(')
    if (marker < 0) return ''

    const start = marker + '@props('.length
    let quote = ''
    let escaped = false
    let depth = 1

    for (let index = start; index < source.length; index += 1) {
        const character = source[index]
        if (quote) {
            if (escaped) escaped = false
            else if (character === '\\') escaped = true
            else if (character === quote) quote = ''
            continue
        }
        if (character === "'" || character === '"') quote = character
        else if (character === '(') depth += 1
        else if (character === ')' && --depth === 0) return source.slice(start, index)
    }

    return ''
}

function splitTopLevel(value: string) {
    const result: string[] = []
    let start = 0
    let quote = ''
    let escaped = false
    let round = 0
    let square = 0
    let curly = 0

    for (let index = 0; index < value.length; index += 1) {
        const character = value[index]
        if (quote) {
            if (escaped) escaped = false
            else if (character === '\\') escaped = true
            else if (character === quote) quote = ''
            continue
        }
        if (character === "'" || character === '"') quote = character
        else if (character === '(') round += 1
        else if (character === ')') round -= 1
        else if (character === '[') square += 1
        else if (character === ']') square -= 1
        else if (character === '{') curly += 1
        else if (character === '}') curly -= 1
        else if (character === ',' && round === 0 && square <= 1 && curly === 0) {
            result.push(value.slice(start, index).trim())
            start = index + 1
        }
    }
    result.push(value.slice(start).trim())
    return result.filter(Boolean)
}

function inferType(value: string) {
    if (value === 'true' || value === 'false') return 'bool'
    if (value === 'null' || value === '—') return 'mixed'
    if (value.startsWith('[')) return 'array'
    if (/^-?\d+$/.test(value)) return 'int'
    if (/^-?\d+\.\d+$/.test(value)) return 'float'
    return 'string'
}

function parseProps(source: string, partName: string): ComponentProp[] {
    const expression = readBalancedProps(source).trim().replace(/^\[/, '').replace(/\]\s*$/, '')
    if (!expression) return []

    return splitTopLevel(expression).map((entry) => {
        const match = entry.match(/^['"]([^'"]+)['"]\s*(?:=>\s*([\s\S]+))?$/)
        if (!match) return null
        const rawName = match[1]
        const defaultValue = match[2]?.trim() || '—'
        return {
            name: kebab(rawName),
            type: inferType(defaultValue),
            defaultValue,
            description: partDescriptions[partName]?.[rawName] ?? descriptions[rawName] ?? `${rawName} 값을 지정합니다.`,
        }
    }).filter((prop): prop is ComponentProp => prop !== null)
}

function parseParts(slug: string, packageRoot: string): ComponentPart[] {
    const documentPath = path.resolve(process.cwd(), 'content/components', `${slug}.mdx`)
    if (!existsSync(documentPath)) return []

    const document = readFileSync(documentPath, 'utf8')
    const names = document.match(/^parts:\s*\[([^\]]*)]/m)?.[1]
        .split(',')
        .map((part) => part.trim())
        .filter(Boolean) ?? []

    return names.flatMap((name) => {
        const componentPath = path.join(packageRoot, 'resources/views/components', `${name}.blade.php`)
        if (!existsSync(componentPath)) return []
        const source = readFileSync(componentPath, 'utf8')
        const namedSlots = Array.from(source.matchAll(/@isset\(\$(\w+)\)/g), (match) => kebab(match[1]))
        const slots = [...new Set([...(source.includes('$slot') ? ['default'] : []), ...namedSlots])]
        return [{name, props: parseProps(source, name), slots}]
    })
}

export function ComponentApi({slug}: {slug: string}) {
    const packageRoot = findPackageRoot()
    if (!packageRoot) return null
    const parts = parseParts(slug, packageRoot)
    if (parts.length === 0) return null

    return (
        <section id="api-reference" className="jds-api-reference">
            <h2>API 안내</h2>
            <p>Blade 컴포넌트에서 사용할 수 있는 속성, 기본값과 슬롯입니다.</p>
            <div className="jds-api-parts" aria-label="구성 요소">
                {parts.map((part) => <code key={part.name}>&lt;x-{part.name}&gt;</code>)}
            </div>

            {parts.map((part) => (
                <section key={part.name} className="jds-api-part" aria-labelledby={`api-${part.name}`}>
                    <h3 id={`api-${part.name}`}><code>&lt;x-{part.name}&gt;</code></h3>
                    {part.props.length > 0 ? (
                        <div className="jds-api-table-wrap">
                            <table>
                                <thead><tr><th>속성</th><th>타입</th><th>기본값</th><th>설명</th></tr></thead>
                                <tbody>
                                {part.props.map((prop) => (
                                    <tr key={prop.name}>
                                        <td><code>{prop.name}</code></td>
                                        <td><code>{prop.type}</code></td>
                                        <td><code>{prop.defaultValue}</code></td>
                                        <td>{prop.description}</td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    ) : <p>전용 속성이 없습니다. 일반 HTML 속성은 그대로 전달할 수 있습니다.</p>}
                    <div className="jds-api-slots">
                        <strong>슬롯</strong>
                        {part.slots.length > 0
                            ? part.slots.map((slot) => <code key={slot}>{slot}</code>)
                            : <span>기본 슬롯 없이 단독으로 사용합니다.</span>}
                    </div>
                </section>
            ))}
        </section>
    )
}
