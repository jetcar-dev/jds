<?php

return [
    'title' => 'Select',
    'description' => '네이티브 또는 커스텀 선택 목록을 제공합니다.',
    'parts' => [
        0 => 'select',
        1 => 'select-trigger',
        2 => 'select-value',
        3 => 'select-content',
        4 => 'select-group',
        5 => 'select-label',
        6 => 'select-item',
        7 => 'select-separator',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '기본 슬롯에 option을 넣어 브라우저 기본 선택 메뉴와 같은 방식으로 사용합니다.',
            'code' => <<<'BLADE'
<x-select
    name="status"
    value="active"
    placeholder="상태 선택"
    :options="['active' => '활성', 'inactive' => '비활성']"
    :full-width="true"
/>
BLADE,
        ],
        [
            'key' => 'options',
            'title' => '배열로 항목 전달',
            'description' => 'value와 label 배열로 항목을 빠르게 구성합니다.',
            'code' => <<<'BLADE'
<x-select name="status" :options="['active' => '활성', 'inactive' => '비활성']" value="active" />
BLADE,
        ],
        [
            'key' => 'appearance',
            'title' => '형태, 색상과 크기',
            'description' => '모든 입력 컴포넌트와 동일한 공통 값을 사용합니다.',
            'code' => <<<'BLADE'
<x-select variant="flat" color="default" size="xs" :options="$options" />
<x-select variant="outline" color="primary" size="sm" :options="$options" />
<x-select variant="faded" color="success" size="md" :options="$options" />
<x-select variant="ghost" color="danger" size="lg" :options="$options" />
<x-select variant="outline" color="warning" size="xl" :options="$options" />
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => '여러 항목 선택',
            'description' => '여러 항목을 선택해 배열로 제출합니다.',
            'code' => <<<'BLADE'
<x-select name="roles" :options="$roles" :value="['admin', 'manager']" :multiple="true" indicator="checkbox" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '상태',
            'description' => '오류, 필수, 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-select name="required" :options="$options" :required="true" />
<x-select name="invalid" :options="$options" :invalid="true" />
<x-select name="disabled" :options="$options" :disabled="true" />
BLADE,
        ],
    ],
];
