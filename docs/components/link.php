<?php

return [
    'title' => 'Link',
    'description' => '일반 링크와 외부 링크를 공통 스타일로 표시합니다.',
    'parts' => [
        0 => 'link',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'href에 이동할 주소를 지정하고 링크 문구를 기본 슬롯에 넣습니다.',
            'code' => <<<'BLADE'
<x-link href="/members">회원 목록</x-link>
<x-link href="https://example.com" external>외부 문서</x-link>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '문맥에 맞는 링크 강조 방식을 선택합니다.',
            'code' => <<<'BLADE'
<x-link href="/members" variant="flat">Flat</x-link>
<x-link href="/members" variant="outline">Outline</x-link>
<x-link href="/members" variant="faded">Faded</x-link>
<x-link href="/members" variant="ghost">Ghost</x-link>
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => '색상',
            'description' => '링크 의미에 맞는 공통 색상을 사용합니다.',
            'code' => <<<'BLADE'
<x-link color="primary">Primary</x-link>
<x-link color="success">Success</x-link>
<x-link color="warning">Warning</x-link>
<x-link color="danger">Danger</x-link>
BLADE,
        ],
        [
            'key' => 'external',
            'title' => '외부 링크',
            'description' => '외부 주소에는 표시 아이콘과 안전한 rel을 적용합니다.',
            'code' => <<<'BLADE'
<x-link href="https://example.com/docs" external>외부 문서</x-link>
BLADE,
        ],
    ],
];
