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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'key' => 'multiple',
            'title' => 'Multiple',
            'description' => '검색해서 여러 값을 선택합니다.',
            'code' => <<<'BLADE'
<x-combobox name="members" :options="$members" :value="[1, 3]" :multiple="true" placeholder="담당자 선택" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '필수·오류·비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-combobox name="required_team" :options="$teams" :required="true" />
<x-combobox name="invalid_team" :options="$teams" :invalid="true" />
<x-combobox name="disabled_team" :options="$teams" :disabled="true" />
BLADE,
        ],
    ],
];
