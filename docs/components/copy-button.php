<?php

return [
    'title' => 'Copy Button',
    'description' => '지정한 값을 클립보드에 복사합니다.',
    'parts' => [
        0 => 'copy-button',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-copy-button value="JETCAR-ERP-2026" label="코드 복사">
    코드 복사
</x-copy-button>
BLADE,
        ],
        [
            'key' => 'icon-only',
            'title' => 'Icon Only',
            'description' => '좁은 영역에서는 아이콘만 표시합니다.',
            'code' => <<<'BLADE'
<x-copy-button value="ORD-2026-001" label="주문번호 복사" />
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => 'Disabled',
            'description' => '복사할 수 없는 값은 비활성화합니다.',
            'code' => <<<'BLADE'
<x-copy-button value="" label="복사 불가" :disabled="true">복사</x-copy-button>
BLADE,
        ],
    ],
];
