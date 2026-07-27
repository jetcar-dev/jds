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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-link href="/members" variant="default">회원 목록</x-link>
<x-link href="https://example.com" external>외부 문서</x-link>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => 'Variants',
            'description' => '문맥에 맞는 링크 강조 방식을 선택합니다.',
            'code' => <<<'BLADE'
<x-link href="/members" variant="default">Default</x-link>
<x-link href="/members" variant="muted">Muted</x-link>
<x-link href="/members" variant="subtle">Subtle</x-link>
BLADE,
        ],
        [
            'key' => 'external',
            'title' => 'External Link',
            'description' => '외부 주소에는 표시 아이콘과 안전한 rel을 적용합니다.',
            'code' => <<<'BLADE'
<x-link href="https://example.com/docs" external>외부 문서</x-link>
BLADE,
        ],
    ],
];
