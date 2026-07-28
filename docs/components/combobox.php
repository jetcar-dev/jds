<?php

return [
    'title' => 'Combobox',
    'description' => '검색 가능한 선택 목록에서 하나 또는 여러 값을 고릅니다.',
    'parts' => [
        0 => 'combobox',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '목록을 열거나 검색어를 입력해 원하는 항목을 빠르게 선택합니다.',
            'code' => <<<'BLADE'
<x-combobox
    name="workspace_id"
    :options="$workspaces"
    placeholder="사업장 선택"
    search-placeholder="사업장 검색"
/>
BLADE,
        ],
        [
            'key' => 'appearance',
            'title' => '형태, 색상과 크기',
            'description' => 'variant, color, size는 다른 입력 컴포넌트와 같은 값을 사용합니다.',
            'code' => <<<'BLADE'
<x-combobox variant="flat" color="default" size="xs" :options="$options" />
<x-combobox variant="outline" color="primary" size="sm" :options="$options" />
<x-combobox variant="faded" color="success" size="md" :options="$options" />
<x-combobox variant="ghost" color="danger" size="lg" :options="$options" />
<x-combobox variant="outline" color="warning" size="xl" :options="$options" />
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => '여러 항목 선택',
            'description' => '검색해서 여러 값을 선택합니다.',
            'code' => <<<'BLADE'
<x-combobox name="members" :options="$members" :value="[1, 3]" :multiple="true" placeholder="담당자 선택" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '상태',
            'description' => '필수·오류·비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-combobox name="required_team" :options="$teams" :required="true" />
<x-combobox name="invalid_team" :options="$teams" :invalid="true" />
<x-combobox name="disabled_team" :options="$teams" :disabled="true" />
BLADE,
        ],
    ],
];
