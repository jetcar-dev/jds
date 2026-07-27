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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-icon name="settings-linear" />
<x-icon name="download-minimalistic-linear" />
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => 'Sizes',
            'description' => '일반 HTML style로 아이콘 크기를 조절합니다.',
            'code' => <<<'BLADE'
<x-icon name="settings-linear" style="width: 1rem; height: 1rem" />
<x-icon name="settings-linear" style="width: 1.5rem; height: 1.5rem" />
<x-icon name="settings-linear" style="width: 2rem; height: 2rem" />
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => 'Colors',
            'description' => 'currentColor를 사용하므로 color 속성을 상속합니다.',
            'code' => <<<'BLADE'
<x-icon name="info-circle-linear" style="color: #2563eb" />
<x-icon name="danger-circle-linear" style="color: #dc2626" />
<x-icon name="check-read-linear" style="color: #16a34a" />
BLADE,
        ],
    ],
];
