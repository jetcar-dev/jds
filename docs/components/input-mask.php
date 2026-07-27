<?php

return [
    'title' => 'Input Mask',
    'description' => '전화번호와 사업자번호처럼 형식이 정해진 값을 입력합니다.',
    'parts' => [
        0 => 'input-mask',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-input-mask
    name="phone"
    mask="010-9999-9999"
    inputmode="numeric"
    placeholder="010-0000-0000"
/>
BLADE,
        ],
        [
            'key' => 'business-number',
            'title' => 'Business Number',
            'description' => '사업자등록번호 형식을 강제합니다.',
            'code' => <<<'BLADE'
<x-input-mask name="business_number" mask="999-99-99999" placeholder="000-00-00000" inputmode="numeric" />
BLADE,
        ],
        [
            'key' => 'alphanumeric',
            'title' => 'Alphanumeric',
            'description' => '영문과 숫자가 섞인 코드 형식을 입력합니다.',
            'code' => <<<'BLADE'
<x-input-mask name="product_code" mask="AAA-0000" placeholder="ABC-1234" />
BLADE,
        ],
    ],
];
