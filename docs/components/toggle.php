<?php

return [
    'title' => 'Toggle',
    'description' => '단일 상태 또는 그룹의 선택 상태를 버튼 형태로 전환합니다.',
    'parts' => [
        0 => 'toggle',
        1 => 'toggle-group',
        2 => 'toggle-group-item',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-toggle-group type="single" value="left" variant="outline">
    <x-toggle-group-item value="left">왼쪽</x-toggle-group-item>
    <x-toggle-group-item value="center">가운데</x-toggle-group-item>
    <x-toggle-group-item value="right">오른쪽</x-toggle-group-item>
</x-toggle-group>
BLADE,
        ],
        [
            'key' => 'single',
            'title' => 'Single Toggle',
            'description' => '하나의 표시 상태를 켜고 끕니다.',
            'code' => <<<'BLADE'
<x-toggle :pressed="true" aria-label="굵게">굵게</x-toggle>
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => 'Multiple Selection',
            'description' => '그룹에서 여러 항목을 동시에 선택합니다.',
            'code' => <<<'BLADE'
<x-toggle-group type="multiple" :value="['bold', 'italic']" variant="outline"><x-toggle-group-item value="bold">굵게</x-toggle-group-item><x-toggle-group-item value="italic">기울임</x-toggle-group-item><x-toggle-group-item value="underline">밑줄</x-toggle-group-item></x-toggle-group>
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => 'Sizes',
            'description' => '작업 밀도에 맞춰 크기를 선택합니다.',
            'code' => <<<'BLADE'
<x-toggle size="sm">Small</x-toggle>
<x-toggle size="default">Default</x-toggle>
<x-toggle size="lg">Large</x-toggle>
BLADE,
        ],
    ],
];
