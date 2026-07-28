<?php

return [
    'title' => 'Image',
    'description' => '화면 비율과 로딩 표시, 불러오기 실패 시 대체 화면을 지원하는 이미지입니다.',
    'parts' => [
        0 => 'image',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'src와 alt를 지정해 이미지를 표시합니다. alt에는 이미지의 의미를 설명하는 문장을 작성합니다.',
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
            'title' => '화면 비율',
            'description' => '같은 이미지를 여러 비율로 표시합니다.',
            'code' => <<<'BLADE'
<x-image src="/images/office.svg" alt="사무실" ratio="1 / 1" />
<x-image src="/images/office.svg" alt="사무실" ratio="16 / 9" />
BLADE,
        ],
        [
            'key' => 'fit',
            'title' => '이미지 맞춤 방식',
            'description' => 'cover와 contain 표시 방식을 선택합니다.',
            'code' => <<<'BLADE'
<x-image src="/images/office.svg" alt="사무실" ratio="4 / 3" fit="cover" />
<x-image src="/images/office.svg" alt="사무실" ratio="4 / 3" fit="contain" />
BLADE,
        ],
        [
            'key' => 'rounded',
            'title' => '모서리와 프로필 이미지',
            'description' => '콘텐츠 이미지와 프로필 이미지에 맞춰 radius를 조절합니다.',
            'code' => <<<'BLADE'
<x-image src="/images/office.svg" alt="사업장" ratio="16 / 9" rounded="xl" style="width: 20rem;" />
<x-image src="/images/office.svg" alt="담당자" ratio="1 / 1" rounded="full" style="width: 5rem;" />
BLADE,
        ],
    ],
];
