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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-select name="status" value="active" placeholder="상태 선택" :full-width="true">
    <x-select-trigger><x-select-value /></x-select-trigger>
    <x-select-content>
        <x-select-item value="active">활성</x-select-item>
        <x-select-item value="inactive">비활성</x-select-item>
    </x-select-content>
</x-select>
BLADE,
        ],
        [
            'key' => 'options',
            'title' => 'Options Array',
            'description' => 'value와 label 배열로 항목을 빠르게 구성합니다.',
            'code' => <<<'BLADE'
<x-select name="status" :options="['active' => '활성', 'inactive' => '비활성']" value="active" />
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => 'Multiple',
            'description' => '여러 항목을 선택해 배열로 제출합니다.',
            'code' => <<<'BLADE'
<x-select name="roles" :options="$roles" :value="['admin', 'manager']" :multiple="true" indicator="checkbox" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '오류, 필수, 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-select name="required" :options="$options" :required="true" />
<x-select name="invalid" :options="$options" :invalid="true" />
<x-select name="disabled" :options="$options" :disabled="true" />
BLADE,
        ],
    ],
];
