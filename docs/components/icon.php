<?php

return [
    'title' => 'Icon',
    'description' => '아이콘 이름으로 렌더링합니다.',
    'parts' => [
        0 => 'icon',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'Solar 아이콘 이름을 name에 전달해 표시합니다.',
            'code' => <<<'BLADE'
<x-icon name="settings-linear" />
<x-icon name="download-minimalistic-linear" />
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => '크기',
            'description' => '일반 HTML style로 아이콘 크기를 조절합니다.',
            'code' => <<<'BLADE'
<x-icon name="settings-linear" style="width: 1rem; height: 1rem" />
<x-icon name="settings-linear" style="width: 1.5rem; height: 1.5rem" />
<x-icon name="settings-linear" style="width: 2rem; height: 2rem" />
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => '색상',
            'description' => 'currentColor를 사용하므로 color 속성을 상속합니다.',
            'code' => <<<'BLADE'
<x-icon name="info-circle-linear" style="color: #2563eb" />
<x-icon name="danger-circle-linear" style="color: #dc2626" />
<x-icon name="check-read-linear" style="color: #16a34a" />
BLADE,
        ],
        [
            'key' => 'with-controls',
            'title' => '다른 컴포넌트와 함께 사용',
            'description' => 'Button과 Badge 안에서는 텍스트 크기에 맞춰 아이콘 크기가 자동으로 조정됩니다.',
            'code' => <<<'BLADE'
<x-button><x-icon name="add-circle-linear" /> 차량 등록</x-button>
<x-button variant="bordered" :icon-only="true" aria-label="설정"><x-icon name="settings-linear" /></x-button>
<x-badge color="success"><x-icon name="check-circle-linear" /> 승인</x-badge>
BLADE,
        ],
    ],
];
