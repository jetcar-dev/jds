<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jetcar\Jds\JdsServiceProvider;

$buildComponentDocs = static function (): array {
    $propertyDescriptions = [
        'accept' => '허용할 MIME 타입 또는 확장자를 지정합니다.',
        'align' => '컴포넌트 내부 항목의 정렬 방향입니다.',
        'alphanumeric' => '숫자뿐 아니라 영문 입력도 허용합니다.',
        'alt' => '이미지를 설명하는 대체 텍스트입니다.',
        'ariaLabel' => '보조 기술에 전달할 접근성 이름입니다.',
        'appearance' => '이전 탭 API와의 호환 속성입니다. 새 코드에서는 variant를 사용합니다.',
        'as' => '렌더링할 HTML 요소를 직접 지정합니다.',
        'autoComplete' => '브라우저 자동 완성 힌트를 지정합니다.',
        'backdropVariant' => '모달 뒤 배경을 opaque(어둡게), blur(흐리게), transparent(투명하게) 중에서 선택합니다.',
        'buttonVariant' => '내부 날짜 버튼의 variant를 지정합니다.',
        'captionLayout' => '달력 월·연도 캡션 표시 방식을 지정합니다.',
        'checked' => '초기 선택 상태를 지정합니다.',
        'closeOnSelect' => '항목 선택 직후 메뉴를 닫을지 결정합니다.',
        'collapsible' => '열린 항목을 다시 눌러 모두 닫을 수 있게 합니다.',
        'collapse' => '좁은 화면에서 그룹 자식을 세로로 배치합니다.',
        'color' => 'default(기본), primary(주요), secondary(보조), success(성공), warning(주의), danger(위험) 중에서 선택합니다.',
        'defaultMonth' => '달력을 처음 열었을 때 표시할 월입니다.',
        'deletable' => '파일 삭제 버튼을 표시합니다.',
        'disabled' => '사용자 입력과 상호작용을 비활성화합니다.',
        'disableNavigation' => '달력의 이전·다음 월 이동을 막습니다.',
        'downloadAllUrl' => '여러 기존 파일을 ZIP 등으로 내려받을 서버 주소입니다.',
        'empty' => '검색 결과가 없을 때 표시할 문구입니다.',
        'expanded' => '트리 노드의 초기 펼침 상태입니다.',
        'external' => '외부 링크 표시와 안전한 rel 속성을 적용합니다.',
        'files' => '새로고침 후 복원할 기존 파일 메타데이터 배열입니다.',
        'fit' => '이미지 object-fit 값을 지정합니다.',
        'for' => '연결 대상 요소의 id를 지정합니다.',
        'fullWidth' => '컴포넌트 너비를 부모의 100%로 확장합니다.',
        'fullscreen' => '모달 콘텐츠를 전체 화면으로 표시합니다.',
        'href' => '링크가 이동할 주소입니다.',
        'hourCycle' => '12시간제, 24시간제 또는 locale 자동 방식을 지정합니다.',
        'icon' => '표시할 아이콘 또는 아이콘 유형을 지정합니다.',
        'iconPosition' => '아이콘을 텍스트의 왼쪽 또는 오른쪽에 배치합니다.',
        'iconOnly' => '글자 없이 아이콘만 표시하는 정사각형 버튼으로 만듭니다.',
        'id' => '요소의 고유 HTML id입니다.',
        'indicator' => '선택 상태 표시 아이콘 종류를 지정합니다.',
        'indeterminate' => '체크박스를 일부 선택 상태로 표시합니다.',
        'inputmode' => '모바일 키보드 입력 모드를 지정합니다.',
        'invalid' => '오류 상태 스타일과 aria-invalid를 적용합니다.',
        'inputType' => '시간을 input(직접 입력) 또는 select(목록 선택) 방식으로 입력합니다.',
        'isDismissable' => '배경을 클릭해 모달을 닫을 수 있게 합니다.',
        'isKeyboardDismissDisabled' => 'Escape 키로 모달이 닫히지 않게 합니다.',
        'keyName' => 'JSON 노드 앞에 표시할 키 이름입니다.',
        'label' => '버튼 또는 현재 값의 접근성 라벨입니다.',
        'items' => '간단한 사용을 위한 항목 배열입니다. 복잡한 내용은 기존 slot 조합을 사용할 수 있습니다.',
        'description' => '컴포넌트 아래에 표시할 보조 설명입니다.',
        'error' => '표시할 오류 문자열 또는 오류 메시지 배열입니다.',
        'locale' => '달력 날짜와 요일에 사용할 locale입니다.',
        'mask' => '9(숫자), a(영문), A(대문자), *(영문·숫자)와 구분자로 입력 형식을 지정합니다.',
        'max' => '선택하거나 입력할 수 있는 최댓값입니다.',
        'maxDate' => '선택 가능한 마지막 날짜입니다.',
        'maxDays' => '범위 선택의 최대 일수입니다.',
        'maxFiles' => '추가할 수 있는 전체 파일 개수입니다.',
        'maxFileSize' => '파일 하나의 최대 크기(MB)입니다.',
        'maxlength' => '입력할 OTP 문자 수입니다.',
        'maxNights' => '숙박 범위의 최대 박 수입니다.',
        'maxRows' => 'Textarea가 자동 확장할 최대 행 수입니다.',
        'min' => '선택하거나 입력할 수 있는 최솟값입니다.',
        'minDate' => '선택 가능한 첫 날짜입니다.',
        'minDays' => '범위 선택의 최소 일수입니다.',
        'minNights' => '숙박 범위의 최소 박 수입니다.',
        'minuteStep' => '분 선택 간격입니다.',
        'mode' => 'single(하나 선택) 또는 range(범위 선택) 방식을 지정합니다.',
        'modifiers' => '특정 날짜 상태를 계산하는 modifier 목록입니다.',
        'modifiersClass' => 'modifier별로 적용할 CSS 클래스입니다.',
        'multiple' => '여러 값을 선택하거나 여러 파일을 허용합니다.',
        'name' => '폼 제출에 사용할 필드 이름입니다.',
        'native' => '커스텀 UI 대신 브라우저 네이티브 컨트롤을 사용합니다.',
        'numberOfMonths' => '동시에 표시할 달력 월 개수입니다.',
        'open' => '초기 열린 상태를 지정합니다.',
        'options' => '선택 가능한 value => label 데이터입니다.',
        'orientation' => 'horizontal(가로) 또는 vertical(세로)로 배치합니다.',
        'outOfRange' => '범위 밖 날짜를 disable(선택 금지) 또는 flag(오류 표시)로 처리합니다.',
        'part' => '범위 시간 입력에서 시작/종료 부분을 구분합니다.',
        'placeholder' => '값이 없을 때 표시할 안내 문구입니다.',
        'previewable' => '파일 클릭 시 전체 화면 미리보기를 엽니다.',
        'presets' => '날짜 빠른 선택 프리셋을 활성화하거나 목록을 전달합니다.',
        'pressed' => 'Toggle의 초기 눌림 상태입니다.',
        'ratio' => '이미지 영역의 CSS aspect-ratio입니다.',
        'required' => '필수 입력 상태와 required 속성을 적용합니다.',
        'rootLabel' => 'JSON 루트 노드에 표시할 이름입니다.',
        'rounded' => '이미지 모서리 CSS 클래스를 지정합니다.',
        'rows' => 'Textarea의 초기 표시 행 수입니다.',
        'searchable' => 'Combobox 검색 입력을 표시합니다.',
        'searchPlaceholder' => '검색 입력의 placeholder입니다.',
        'secondStep' => '초 선택 간격입니다.',
        'separatorAt' => 'OTP 입력 칸을 나눌 위치 또는 위치 배열입니다.',
        'selection' => '그룹 안 Toggle의 선택 규칙을 single 또는 multiple로 지정합니다.',
        'seconds' => '시간 입력에 초 단위를 표시합니다.',
        'showClose' => '모달 우측 상단 닫기 버튼을 표시합니다.',
        'showOutsideDays' => '현재 월 밖의 날짜를 달력에 표시합니다.',
        'showWeekNumber' => '달력에 주차 번호 열을 표시합니다.',
        'side' => '팝오버가 열릴 기본 방향입니다.',
        'sideOffset' => '트리거와 팝오버 사이 간격(px)입니다.',
        'size' => 'xs(아주 작게), sm(작게), md(보통), lg(크게), xl(아주 크게) 중에서 선택합니다.',
        'src' => '표시할 이미지 주소입니다.',
        'startMonth' => '달력 탐색이 가능한 첫 월입니다.',
        'endMonth' => '달력 탐색이 가능한 마지막 월입니다.',
        'timeInputType' => '날짜·시간 선택기의 시간을 input(직접 입력) 또는 select(목록 선택) 방식으로 입력합니다.',
        'toggle' => '비밀번호 보기처럼 입력 우측 toggle 기능을 지정합니다.',
        'trigger' => 'Combobox 트리거 UI 유형을 지정합니다.',
        'triggerText' => '자동 생성할 트리거 버튼의 문구입니다.',
        'triggerVariant' => '자동 생성하는 트리거 버튼의 variant입니다.',
        'cancelText' => '자동 생성할 취소 버튼의 문구입니다.',
        'confirmText' => '자동 생성할 확인 버튼의 문구입니다.',
        'confirmVariant' => '자동 생성하는 확인 버튼의 variant입니다.',
        'type' => '컴포넌트 동작 또는 HTML input/button 타입입니다.',
        'value' => '초기 선택값 또는 입력값입니다.',
        'valueName' => '기존 파일 ID를 제출할 hidden input 이름입니다.',
        'variant' => '컴포넌트 형태를 선택합니다. 버튼·단일 행 입력은 solid, faded, bordered, light, flat, ghost, shadow를 사용하고 탭과 긴 입력은 해당 문서의 전용 목록을 사용합니다.',
        'weekStart' => '한 주의 시작 요일을 0~6 또는 요일 이름으로 지정합니다.',
        'width' => '팝오버 또는 컴포넌트 너비를 지정합니다.',
    ];

    $propertyTypes = [
        'accept' => 'string|null', 'align' => 'string',
        'ariaLabel' => 'string|null', 'as' => 'string|null', 'autoComplete' => 'string|null',
        'color' => 'string|null', 'colorForeground' => 'string|null', 'defaultMonth' => 'string|null',
        'downloadAllUrl' => 'string|null', 'endMonth' => 'string|null',
        'files' => 'array', 'for' => 'string|null', 'href' => 'string|null', 'icon' => 'string|null',
        'items' => 'array|iterable|null', 'label' => 'string|null', 'description' => 'string|null',
        'collapse' => 'bool', 'selection' => 'string|null',
        'error' => 'string|array|null', 'separatorAt' => 'int|array|null',
        'id' => 'string|null', 'keyName' => 'string|int|null', 'locale' => 'string|null',
        'max' => 'string|null', 'maxDate' => 'string|null', 'maxFiles' => 'int|null',
        'maxNights' => 'int|null', 'maxRows' => 'int|null', 'min' => 'string|null',
        'minDate' => 'string|null', 'minNights' => 'int|null', 'name' => 'string|null',
        'numberOfMonths' => 'int|null', 'options' => 'array|iterable|null', 'part' => 'string|null',
        'placeholder' => 'string|null', 'presets' => 'bool|array|null', 'rootLabel' => 'string|null',
        'rows' => 'int|null', 'startMonth' => 'string|null', 'toggle' => 'string|null',
        'triggerText' => 'string|null', 'triggerVariant' => 'string',
        'cancelText' => 'string|null', 'confirmText' => 'string|null', 'confirmVariant' => 'string',
        'value' => 'mixed', 'valueName' => 'string|null', 'width' => 'string|int|null',
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

    $readProps = static function (string $source) use ($inferType, $propertyDescriptions, $propertyTypes): array {
        $marker = strpos($source, '@props(');
        if ($marker === false) {
            return [];
        }

        $start = $marker + strlen('@props(');
        $depth = 1;
        $quote = null;
        $end = $start;
        for ($index = $start, $length = strlen($source); $index < $length; $index++) {
            $character = $source[$index];
            if ($quote !== null) {
                if ($character === $quote && $source[$index - 1] !== '\\') {
                    $quote = null;
                }
                continue;
            }
            if ($character === "'" || $character === '"') {
                $quote = $character;
                continue;
            }
            if ($character === '(') $depth++;
            if ($character === ')') $depth--;
            if ($depth === 0) {
                $end = $index;
                break;
            }
        }

        $expression = substr($source, $start, $end - $start);
        preg_match_all("/'([^']+)'\\s*=>\\s*('(?:\\\\'|[^'])*'|\"(?:\\\\\"|[^\"])*\"|\\[[^\\]]*\\]|[^,\\r\\n\\]]+)/", $expression, $matches, PREG_SET_ORDER);

        return array_map(static function (array $match) use ($inferType, $propertyDescriptions, $propertyTypes): array {
            $default = trim($match[2]);
            return [
                'name' => Str::kebab($match[1]),
                'type' => $match[1] === 'disabled' && $default === 'null'
                    ? 'bool|array|null'
                    : ($propertyTypes[$match[1]] ?? $inferType($default)),
                'default' => $default,
                'description' => $propertyDescriptions[$match[1]] ?? Str::headline($match[1]) . ' 값을 지정합니다.',
            ];
        }, $matches);
    };

    $documentFiles = glob(base_path('components/*.php')) ?: [];
    natcasesort($documentFiles);
    $catalog = collect($documentFiles)->mapWithKeys(function (string $file): array {
        return [pathinfo($file, PATHINFO_FILENAME) => require $file];
    })->all();
    $providerFile = (new ReflectionClass(JdsServiceProvider::class))->getFileName();
    $componentRoot = dirname($providerFile, 2) . '/resources/views/components';
    $componentDocs = collect($catalog)->map(function (array $family, string $slug) use ($componentRoot, $readProps) {
        $family['slug'] = $slug;
        $family['components'] = collect($family['parts'])->map(function (string $name) use ($componentRoot, $readProps) {
            $source = file_get_contents($componentRoot . '/' . $name . '.blade.php');
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

    return $componentDocs;
};

Route::redirect('/', '/installation')->name('home');

Route::get('/installation', function () use ($buildComponentDocs) {
    return view('installation', [
        'componentDocs' => $buildComponentDocs(),
        'assetCode' => <<<'BLADE'
<link rel="stylesheet" href="{{ asset('vendor/jds/jds.css') }}">
<script type="module" src="{{ asset('vendor/jds/jds.js') }}"></script>
BLADE,
        'usageCode' => <<<'BLADE'
<x-button variant="outline" size="sm">저장</x-button>
<x-date-picker :show-outside-days="false" :presets="$presets" />
BLADE,
    ]);
})->name('installation');

Route::view('/component-test', 'component-test')->name('component-test');

Route::get('/components/{component}', function (string $component) use ($buildComponentDocs) {
    $componentDocs = $buildComponentDocs();
    abort_unless(array_key_exists($component, $componentDocs), 404);

    return view('component', [
        'componentDocs' => $componentDocs,
        'doc' => $componentDocs[$component],
        'workspaces' => [
            1 => '제트카 본사',
            2 => '서울지점',
            3 => '부산지점',
        ],
    ]);
})->name('components.show');
