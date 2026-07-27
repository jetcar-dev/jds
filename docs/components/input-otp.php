<?php

return [
    'title' => 'Input OTP',
    'description' => '인증번호를 여러 칸으로 나누어 입력합니다.',
    'parts' => [
        0 => 'input-otp',
        1 => 'input-otp-group',
        2 => 'input-otp-slot',
        3 => 'input-otp-separator',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-input-otp name="verification_code" :maxlength="6">
    <x-input-otp-group>
        @for ($i = 0; $i < 6; $i++)
            <x-input-otp-slot :index="$i" />
        @endfor
    </x-input-otp-group>
</x-input-otp>
BLADE,
        ],
        [
            'key' => 'separated',
            'title' => 'Separated Groups',
            'description' => '코드를 두 그룹으로 나눠 읽기 쉽게 표시합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="code" :maxlength="6"><x-input-otp-group>@for($i=0;$i<3;$i++)<x-input-otp-slot :index="$i" />@endfor</x-input-otp-group><x-input-otp-separator /><x-input-otp-group>@for($i=3;$i<6;$i++)<x-input-otp-slot :index="$i" />@endfor</x-input-otp-group></x-input-otp>
BLADE,
        ],
        [
            'key' => 'alphanumeric',
            'title' => 'Alphanumeric',
            'description' => '문자와 숫자가 섞인 인증 코드를 입력합니다.',
            'code' => <<<'BLADE'
<x-input-otp name="invite_code" value="A2C4E6" :maxlength="6" :alphanumeric="true" />
BLADE,
        ],
    ],
];
