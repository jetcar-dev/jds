<?php

return [
    'title' => 'Image',
    'description' => '비율, placeholder, 오류 fallback을 지원하는 이미지입니다.',
    'parts' => [
        0 => 'image',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-image
    src="/images/office.svg"
    placeholder="/images/office.svg"
    alt="사무실"
    ratio="16 / 9"
    fit="cover"
/>
BLADE,
        ],
        [
            'key' => 'ratios',
            'title' => 'Aspect Ratios',
            'description' => '같은 이미지를 여러 비율로 표시합니다.',
            'code' => <<<'BLADE'
<x-image src="/images/office.svg" alt="사무실" ratio="1 / 1" />
<x-image src="/images/office.svg" alt="사무실" ratio="16 / 9" />
BLADE,
        ],
        [
            'key' => 'fit',
            'title' => 'Object Fit',
            'description' => 'cover와 contain 표시 방식을 선택합니다.',
            'code' => <<<'BLADE'
<x-image src="/images/office.svg" alt="사무실" ratio="4 / 3" fit="cover" />
<x-image src="/images/office.svg" alt="사무실" ratio="4 / 3" fit="contain" />
BLADE,
        ],
    ],
];
