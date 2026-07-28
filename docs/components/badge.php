<?php

return [
    'title' => 'Badge',
    'description' => '상태, 분류, 짧은 정보를 작은 라벨로 표시합니다.',
    'parts' => [
        0 => 'badge',
    ],
    'examples' => [
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '기본 강조 수준에 맞는 variant를 선택합니다.',
            'code' => <<<'BLADE'
<x-badge variant="flat">Flat</x-badge>
<x-badge variant="outline">Outline</x-badge>
<x-badge variant="faded">Faded</x-badge>
<x-badge variant="ghost">Ghost</x-badge>
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => '색상',
            'description' => '모든 컴포넌트와 동일한 color 값을 사용합니다.',
            'code' => <<<'BLADE'
<x-badge color="default">기본</x-badge>
<x-badge color="primary">정보</x-badge>
<x-badge color="secondary">보조</x-badge>
<x-badge color="success">완료</x-badge>
<x-badge color="warning">대기</x-badge>
<x-badge color="danger">오류</x-badge>
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => '크기',
            'description' => '공통 다섯 단계 크기를 사용합니다.',
            'code' => <<<'BLADE'
<x-badge size="xs">Extra Small</x-badge>
<x-badge size="sm">Small</x-badge>
<x-badge size="md">Medium</x-badge>
<x-badge size="lg">Large</x-badge>
<x-badge size="xl">Extra Large</x-badge>
BLADE,
        ],
        [
            'key' => 'icon-link',
            'title' => '아이콘과 링크',
            'description' => '아이콘을 넣거나 링크형 배지로 사용할 수 있습니다.',
            'code' => <<<'BLADE'
<x-badge color="success"><x-icon name="check-circle-linear" /> 승인</x-badge>
<x-badge href="/components/badge" variant="outline">상세 보기</x-badge>
BLADE,
        ],
    ],
];
