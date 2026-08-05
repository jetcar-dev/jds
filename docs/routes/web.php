<?php

use App\Support\MdxComponentDocument;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$buildComponentDocs = static function (): array {
    $descriptions = [
        'backdrop' => '배경 효과를 opaque, blur, transparent 중에서 선택합니다.',
        'checked' => '초기 선택 상태를 지정합니다.',
        'clearable' => '입력값을 지우는 버튼을 표시합니다.',
        'closable' => '사용자가 닫을 수 있는 버튼을 표시합니다.',
        'color' => 'default, primary, secondary, success, warning, danger 중에서 선택합니다.',
        'disabled' => '사용자 입력과 상호작용을 비활성화합니다.',
        'dismissable' => '바깥 영역을 눌러 Overlay를 닫을 수 있게 합니다.',
        'errorMessage' => 'invalid 상태에서 보여줄 오류 설명입니다.',
        'fullWidth' => '사용 가능한 가로 너비를 모두 사용합니다.',
        'iconOnly' => '아이콘만 표시하는 정사각형 버튼으로 만듭니다.',
        'invalid' => '오류 스타일과 aria-invalid 상태를 적용합니다.',
        'keyboardDismissDisabled' => 'Escape 키로 Overlay를 닫지 못하게 합니다.',
        'label' => '보이는 라벨과 접근 가능한 이름을 제공합니다.',
        'loading' => '진행 표시를 보여주고 상호작용을 잠급니다.',
        'name' => '일반 HTML 폼으로 제출할 필드 이름입니다.',
        'orientation' => 'horizontal 또는 vertical 배치 방향입니다.',
        'placeholder' => '값이 없을 때 보여줄 안내 문구입니다.',
        'radius' => 'none, sm, md, lg, full 중에서 모서리 크기를 선택합니다.',
        'required' => '필수 입력과 접근성 상태를 적용합니다.',
        'scrollBehavior' => 'inside 또는 outside에서 긴 Modal 내용을 스크롤합니다.',
        'selectionMode' => 'none, single, multiple 또는 range 선택 규칙입니다.',
        'showValueLabel' => '현재 진행 값을 텍스트로 함께 표시합니다.',
        'size' => 'sm, md, lg 중에서 크기를 선택합니다.',
        'value' => '초기값 또는 현재 선택값입니다.',
        'variant' => '컴포넌트가 지원하는 시각적 형태를 선택합니다.',
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

    $readProps = static function (string $source) use ($descriptions, $inferType): array {
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

        return array_map(static function (array $match) use ($descriptions, $inferType): array {
            $name = $match[1];
            $default = trim($match[2]);
            return [
                'name' => Str::kebab($name),
                'type' => $inferType($default),
                'default' => $default,
                'description' => $descriptions[$name] ?? Str::headline($name).' 값을 지정합니다.',
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
                'props' => $readProps($source),
                'slots' => str_contains($source, '$slot') ? [[
                    'name' => 'default',
                    'description' => '컴포넌트 내부에 표시할 자식 콘텐츠입니다.',
                ]] : [],
            ];
        })->all();
        return $family;
    })->all();
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

Route::get('/components/{component}', function (string $component) use ($buildComponentDocs) {
    $componentDocs = $buildComponentDocs();
    abort_unless(array_key_exists($component, $componentDocs), 404);
    return view('component-mdx', [
        'componentDocs' => $componentDocs,
        'doc' => $componentDocs[$component],
    ]);
})->name('components.show');
