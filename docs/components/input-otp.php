<?php

return [
    'title' => 'Input OTP',
    'description' => 'maxlength만 지정하면 독립된 field 형태의 인증번호 입력 칸을 자동으로 만듭니다.',
    'parts' => ['input-otp'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'slot이나 반복문 없이 바로 사용합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="verification_code" :maxlength="6" />
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '다른 단일 행 입력 컴포넌트와 동일한 일곱 가지 variant를 사용합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="flat_code" variant="flat" />
<x-input-otp name="faded_code" variant="faded" />
<x-input-otp name="bordered_code" variant="bordered" />
<x-input-otp name="light_code" variant="light" />
<x-input-otp name="solid_code" variant="solid" />
<x-input-otp name="ghost_code" variant="ghost" />
<x-input-otp name="shadow_code" variant="shadow" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '색상, 크기와 상태',
            'description' => '공통 color와 size 단계, invalid, disabled 상태를 지원합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="primary_code" color="primary" />
<x-input-otp name="success_code" color="success" variant="faded" />
<x-input-otp name="small_code" size="sm" />
<x-input-otp name="large_code" size="lg" value="123" />
<x-input-otp name="invalid_code" :invalid="true" />
<x-input-otp name="disabled_code" value="123456" :disabled="true" />
BLADE,
        ],
        [
            'key' => 'separated',
            'title' => '입력 칸 나누기',
            'description' => 'separator-at으로 입력 칸을 나눕니다.',
            'code' => <<<'BLADE'
<x-input-otp name="code" :maxlength="6" :separator-at="3" />
BLADE,
        ],
        [
            'key' => 'alphanumeric',
            'title' => '영문과 숫자 입력',
            'description' => '문자와 숫자가 섞인 코드도 같은 방식으로 사용합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="invite_code" value="A2C4E6" :maxlength="6" :alphanumeric="true" />
BLADE,
        ],
    ],
];
