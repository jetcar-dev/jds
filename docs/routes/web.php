<?php

use App\Support\MdxComponentDocument;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$buildComponentDocs = static function (): array {
    $descriptions = [
        'backdrop' => '배경 효과를 opaque, blur, transparent 중에서 선택합니다.',
        'allowsCustomValue' => '목록에 없는 사용자의 직접 입력값을 허용합니다.',
        'allowsEmptyCollection' => '검색 결과가 없어도 추천 목록을 열어 둡니다.',
        'checked' => '초기 선택 상태를 지정합니다.',
        'clearable' => '입력값을 지우는 버튼을 표시합니다.',
        'closable' => '사용자가 닫을 수 있는 버튼을 표시합니다.',
        'color' => 'default, primary, secondary, success, warning, danger 중에서 선택합니다.',
        'disabled' => '사용자 입력과 상호작용을 비활성화합니다.',
        'dismissable' => '바깥 영역을 눌러 Overlay를 닫을 수 있게 합니다.',
        'defaultSelectedKey' => '처음 렌더링할 때 선택할 항목의 값입니다.',
        'defaultInputValue' => '처음 렌더링할 때 검색 입력에 보여줄 문구입니다.',
        'defaultValue' => '처음 렌더링할 때 사용할 선택값입니다.',
        'description' => '입력 아래에 도움말을 표시합니다.',
        'destroyInactiveTabPanel' => '선택되지 않은 탭 패널을 DOM에서 제거합니다.',
        'disableAnimation' => '움직임 효과를 끄고 선택 상태를 즉시 반영합니다.',
        'disableCursorAnimation' => '탭 사이를 이동하는 선택 표시를 숨깁니다.',
        'disableSelectorIconRotation' => '목록이 열려도 선택 아이콘을 회전하지 않습니다.',
        'disabledKeys' => '선택할 수 없게 만들 항목의 값을 배열로 지정합니다.',
        'errorMessage' => 'invalid 상태에서 보여줄 오류 설명입니다.',
        'fullWidth' => '사용 가능한 가로 너비를 모두 사용합니다.',
        'href' => '항목을 링크로 렌더링할 이동 주소입니다.',
        'id' => '실제 Checkbox input에 적용할 HTML id입니다.',
        'iconOnly' => '아이콘만 표시하는 정사각형 버튼으로 만듭니다.',
        'indeterminate' => '하위 항목 중 일부만 선택된 상태를 표시합니다.',
        'invalid' => '오류 스타일과 aria-invalid 상태를 적용합니다.',
        'inputValue' => '검색 입력에 표시할 현재 문구입니다.',
        'isClearable' => '입력값을 지우는 버튼을 표시합니다.',
        'itemHeight' => '대량 목록에서 사용할 각 항목의 높이입니다.',
        'keyboardDismissDisabled' => 'Escape 키로 Overlay를 닫지 못하게 합니다.',
        'label' => '보이는 라벨과 접근 가능한 이름을 제공합니다.',
        'labelPlacement' => 'inside, outside, outside-left, outside-top 중에서 라벨 위치를 선택합니다.',
        'lineThrough' => '선택된 라벨 중앙에 취소선을 표시합니다.',
        'loading' => '진행 표시를 보여주고 상호작용을 잠급니다.',
        'maxListboxHeight' => '추천 목록이 커질 수 있는 최대 높이입니다.',
        'menuTrigger' => 'focus, input, manual 중에서 추천 목록을 여는 시점을 선택합니다.',
        'name' => '일반 HTML 폼으로 제출할 필드 이름입니다.',
        'orientation' => 'horizontal 또는 vertical 배치 방향입니다.',
        'placement' => 'top, bottom, start, end 중에서 탭 목록의 위치를 선택합니다.',
        'placeholder' => '값이 없을 때 보여줄 안내 문구입니다.',
        'readOnly' => '현재 값을 보여주되 사용자가 변경하지 못하게 합니다.',
        'readonly' => '현재 값을 폼에 포함하되 사용자가 변경하지 못하게 합니다.',
        'radius' => 'none, sm, md, lg, full 중에서 모서리 크기를 선택합니다.',
        'required' => '필수 입력과 접근성 상태를 적용합니다.',
        'scrollBehavior' => 'inside 또는 outside에서 긴 Modal 내용을 스크롤합니다.',
        'selectionMode' => 'none, single, multiple 또는 range 선택 규칙입니다.',
        'selected' => '해당 항목을 처음 선택된 상태로 렌더링합니다.',
        'selectedKey' => '현재 선택할 항목의 값입니다.',
        'showValueLabel' => '현재 진행 값을 텍스트로 함께 표시합니다.',
        'showScrollIndicators' => '스크롤 가능한 추천 목록의 위아래 경계를 표시합니다.',
        'shouldCloseOnBlur' => '입력에서 포커스가 벗어날 때 추천 목록을 닫습니다.',
        'shouldSelectOnPressUp' => 'true이면 포인터를 놓을 때, false이면 누를 때 탭을 선택합니다.',
        'size' => 'sm, md, lg 중에서 크기를 선택합니다.',
        'isVertical' => '탭 목록을 세로로 배치하고 위·아래 방향키를 사용합니다.',
        'keyboardActivation' => 'automatic은 초점 이동과 함께 선택하고 manual은 Enter 또는 Space로 선택합니다.',
        'value' => '초기값 또는 현재 선택값입니다.',
        'variant' => '컴포넌트가 지원하는 시각적 형태를 선택합니다.',
        'virtualized' => '많은 항목에서 화면 밖 렌더링 비용을 줄입니다.',
        'emptyContent' => '검색 결과가 없을 때 표시할 문구입니다.',
        'textValue' => '검색과 접근성 이름에 사용할 항목의 텍스트입니다.',
    ];
    $componentDescriptions = [
        'link' => [
            'color' => 'foreground, primary, secondary, success, warning, danger 중에서 선택합니다.',
            'underline' => 'none, hover, always, active, focus 중에서 밑줄 표시 시점을 선택합니다.',
            'external' => '새 탭으로 안전하게 열기 위한 target과 rel 속성을 추가합니다.',
            'showAnchorIcon' => '링크 끝에 기본 이동 아이콘을 표시합니다.',
            'block' => '링크에 안쪽 여백과 색상별 hover 배경을 적용합니다.',
        ],
        'button' => [
            'href' => '지정하면 실제 링크 요소로 렌더링할 이동 주소입니다.',
            'external' => '링크 버튼을 새 탭으로 안전하게 엽니다.',
            'showAnchorIcon' => '링크 버튼 끝에 Solar 이동 아이콘을 표시합니다.',
        ],
        'switch' => [
            'checked' => '처음 렌더링할 때 선택된 상태로 표시합니다.',
            'required' => '폼 제출 시 반드시 선택해야 하는 값으로 지정합니다.',
            'readonly' => '현재 값을 유지하면서 사용자의 상태 변경만 막습니다.',
            'color' => 'default, primary, secondary, success, warning, danger 중에서 선택합니다.',
        ],
        'dropdown' => [
            'placement' => 'top, bottom, left, right와 start/end 조합으로 메뉴가 열릴 위치를 지정합니다.',
            'offset' => '트리거와 메뉴 사이의 간격을 px 단위로 지정합니다.',
            'triggerScaleOnOpen' => '메뉴가 열릴 때 트리거를 살짝 축소하고 투명도를 낮춥니다.',
        ],
        'dropdown-content' => [
            'variant' => 'solid, bordered, light, flat, faded, shadow 중에서 항목의 상호작용 형태를 선택합니다.',
            'color' => 'default, primary, secondary, success, warning, danger 중에서 선택합니다.',
            'selectionMode' => 'none, single, multiple 중에서 선택 방식을 지정합니다.',
            'selectedKeys' => '처음 선택할 항목 key 목록입니다.',
            'disabledKeys' => '비활성화할 항목 key 목록입니다.',
            'closeOnSelect' => '항목 실행 후 메뉴를 닫을지 지정합니다. multiple은 기본적으로 열린 상태를 유지합니다.',
        ],
        'dropdown-item' => [
            'key' => '작업 이벤트와 선택 상태에서 사용할 고유 식별자입니다.',
            'description' => '항목 제목 아래에 표시할 보조 설명입니다.',
            'shortcut' => '오른쪽에 표시할 안내용 단축키입니다.',
            'closeOnSelect' => '이 항목 실행 후 메뉴를 닫을지 개별 지정합니다.',
        ],
        'card' => [
            'radius' => 'none, sm, md, lg 중에서 모서리 크기를 선택합니다.',
            'shadow' => 'none, sm, md, lg 중에서 그림자 크기를 선택합니다.',
            'pressable' => 'Card 전체를 하나의 버튼으로 사용합니다.',
            'hoverable' => '마우스를 올렸을 때 content2 배경을 적용합니다.',
            'blurred' => 'Card 표면에 반투명 blur 효과를 적용합니다.',
            'footerBlurred' => 'Footer에 반투명 blur 효과를 적용합니다.',
            'disableRipple' => '누를 때 나타나는 ripple 효과를 끕니다.',
            'allowTextSelectionOnPress' => '누를 수 있는 Card 안의 텍스트 선택을 허용합니다.',
        ],
        'modal' => [
            'size' => 'xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, full 중에서 최대 너비를 선택합니다.',
            'radius' => 'none, sm, md, lg 중에서 모서리 크기를 선택합니다.',
            'shadow' => 'none, sm, md, lg 중에서 그림자 크기를 선택합니다.',
            'backdrop' => 'transparent, opaque, blur 중에서 배경 효과를 선택합니다.',
            'placement' => 'auto, top, center, bottom 중에서 표시 위치를 선택합니다.',
            'dismissable' => '배경을 눌러 Modal을 닫을 수 있게 합니다.',
            'keyboardDismissDisabled' => 'Escape 키로 Modal이 닫히지 않게 합니다.',
            'scrollBehavior' => 'normal, inside, outside 중에서 긴 내용의 스크롤 방식을 선택합니다.',
            'hideCloseButton' => '오른쪽 위의 기본 닫기 버튼을 숨깁니다.',
            'disableAnimation' => '열림과 닫힘 애니메이션을 끕니다.',
            'defaultOpen' => '처음 렌더링할 때 Modal을 열린 상태로 표시합니다.',
            'shouldBlockScroll' => 'Modal이 열린 동안 배경 문서의 스크롤을 잠급니다.',
            'portalTarget' => 'Overlay를 렌더링할 요소의 id 또는 CSS 선택자입니다.',
            'motion' => 'Web Animations API 형식으로 enter, exit, options를 지정합니다.',
            'draggable' => 'ModalHeader를 잡고 Modal을 이동할 수 있게 합니다.',
            'draggableOverflow' => '드래그할 때 Modal이 화면 경계를 넘어갈 수 있게 합니다.',
        ],
    ];

    $inferType = static function (string $default): string {
        $value = trim($default);
        return match (true) {
            in_array($value, ['true', 'false'], true) => 'bool',
            $value === 'null' => 'mixed',
            str_starts_with($value, '[') => 'array',
            is_numeric($value) => str_contains($value, '.') ? 'float' : 'int',
            default => 'string',
        };
    };

    $readProps = static function (string $source, ?string $componentName = null) use ($descriptions, $componentDescriptions, $inferType): array {
        $marker = strpos($source, '@props(');
        if ($marker === false) return [];

        $start = $marker + strlen('@props(');
        $depth = 1;
        $quote = null;
        $end = $start;
        for ($index = $start, $length = strlen($source); $index < $length; $index++) {
            $character = $source[$index];
            if ($quote !== null) {
                if ($character === $quote && $source[$index - 1] !== '\\') $quote = null;
                continue;
            }
            if ($character === "'" || $character === '"') { $quote = $character; continue; }
            if ($character === '(') $depth++;
            if ($character === ')') $depth--;
            if ($depth === 0) { $end = $index; break; }
        }

        $expression = substr($source, $start, $end - $start);
        preg_match_all("/'([^']+)'\\s*=>\\s*('(?:\\\\'|[^'])*'|\"(?:\\\\\"|[^\"])*\"|\\[[^\\]]*\\]|[^,\\r\\n\\]]+)/", $expression, $matches, PREG_SET_ORDER);

        return array_map(static function (array $match) use ($componentName, $descriptions, $componentDescriptions, $inferType): array {
            $name = $match[1];
            $default = trim($match[2]);
            return [
                'name' => Str::kebab($name),
                'type' => $inferType($default),
                'default' => $default,
                'description' => $componentDescriptions[$componentName][$name] ?? $descriptions[$name] ?? Str::headline($name).' 값을 지정합니다.',
            ];
        }, $matches);
    };

    $files = glob(base_path('content/components/*.mdx')) ?: [];
    natcasesort($files);
    $catalog = [];
    foreach ($files as $file) $catalog[pathinfo($file, PATHINFO_FILENAME)] = MdxComponentDocument::load($file);
    uksort($catalog, 'strnatcasecmp');

    $componentRoot = base_path('../package/resources/views/components');
    return collect($catalog)->map(function (array $family, string $slug) use ($componentRoot, $readProps) {
        $family['slug'] = $slug;
        $family['components'] = collect($family['parts'])->map(function (string $name) use ($componentRoot, $readProps) {
            $source = file_get_contents("{$componentRoot}/{$name}.blade.php");
            return [
                'name' => $name,
                'props' => $readProps($source, $name),
                'slots' => str_contains($source, '$slot') ? [[
                    'name' => 'default',
                    'description' => '컴포넌트 내부에 표시할 자식 콘텐츠입니다.',
                ]] : [],
            ];
        })->all();
        return $family;
    })->all();
};

$readBundledIcons = static function (): array {
    $bundle = file_get_contents(base_path('../package/resources/js/icons/iconify-bundle.js'));
    preg_match_all('/^\s+"([^"]+)": \{"body":/m', $bundle, $matches);

    return $matches[1] ?? [];
};

Route::redirect('/', '/installation')->name('home');

Route::get('/installation', fn () => view('installation', [
    'componentDocs' => $buildComponentDocs(),
    'usageCode' => <<<'BLADE'
<x-button color="primary">저장</x-button>
<x-date-picker name="date" label="예약일" />
BLADE,
]))->name('installation');

Route::view('/component-test', 'component-test')->name('component-test');

Route::get('/icons', fn () => view('icons', [
    'componentDocs' => $buildComponentDocs(),
    'iconNames' => $readBundledIcons(),
]))->name('icons');

Route::get('/components/{component}', function (string $component) use ($buildComponentDocs) {
    $componentDocs = $buildComponentDocs();
    abort_unless(array_key_exists($component, $componentDocs), 404);
    return view('component-mdx', [
        'componentDocs' => $componentDocs,
        'doc' => $componentDocs[$component],
    ]);
})->name('components.show');
