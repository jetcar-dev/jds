<?php

return [
    'title' => 'Breadcrumb',
    'description' => '현재 페이지의 계층과 이동 경로를 표시합니다.',
    'parts' => [
        0 => 'breadcrumb',
        1 => 'breadcrumb-list',
        2 => 'breadcrumb-item',
        3 => 'breadcrumb-link',
        4 => 'breadcrumb-page',
        5 => 'breadcrumb-separator',
        6 => 'breadcrumb-ellipsis',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-breadcrumb>
    <x-breadcrumb-list>
        <x-breadcrumb-item><x-breadcrumb-link href="/">홈</x-breadcrumb-link></x-breadcrumb-item>
        <x-breadcrumb-separator />
        <x-breadcrumb-item><x-breadcrumb-page>계정</x-breadcrumb-page></x-breadcrumb-item>
    </x-breadcrumb-list>
</x-breadcrumb>
BLADE,
        ],
        [
            'key' => 'ellipsis',
            'title' => 'Collapsed',
            'description' => '경로가 길면 중간 단계를 줄여 표시합니다.',
            'code' => <<<'BLADE'
<x-breadcrumb><x-breadcrumb-list>
    <x-breadcrumb-item><x-breadcrumb-link href="/">홈</x-breadcrumb-link></x-breadcrumb-item><x-breadcrumb-separator />
    <x-breadcrumb-item><x-breadcrumb-ellipsis /></x-breadcrumb-item><x-breadcrumb-separator />
    <x-breadcrumb-item><x-breadcrumb-page>주문 상세</x-breadcrumb-page></x-breadcrumb-item>
</x-breadcrumb-list></x-breadcrumb>
BLADE,
        ],
        [
            'key' => 'custom-separator',
            'title' => 'Custom Separator',
            'description' => '구분 기호 slot을 원하는 문자로 바꿉니다.',
            'code' => <<<'BLADE'
<x-breadcrumb><x-breadcrumb-list>
    <x-breadcrumb-item><x-breadcrumb-link href="/docs">문서</x-breadcrumb-link></x-breadcrumb-item>
    <x-breadcrumb-separator>/</x-breadcrumb-separator>
    <x-breadcrumb-item><x-breadcrumb-page>설치</x-breadcrumb-page></x-breadcrumb-item>
</x-breadcrumb-list></x-breadcrumb>
BLADE,
        ],
    ],
];
