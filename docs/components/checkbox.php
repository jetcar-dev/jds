<?php

return [
    'title' => 'Checkbox',
    'description' => '여러 선택지 또는 동의 여부를 입력합니다.',
    'parts' => [
        0 => 'checkbox',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<label>
    <x-checkbox name="terms" value="1" :checked="true" />
    이용약관에 동의합니다
</label>
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '선택, 일부 선택, 비활성 상태를 비교합니다.',
            'code' => <<<'BLADE'
<x-checkbox :checked="true" aria-label="선택됨" />
<x-checkbox :indeterminate="true" aria-label="일부 선택" />
<x-checkbox :disabled="true" aria-label="비활성" />
BLADE,
        ],
        [
            'key' => 'native',
            'title' => 'Native',
            'description' => 'JavaScript 없이 네이티브 체크박스로 사용합니다.',
            'code' => <<<'BLADE'
<x-checkbox name="agree" value="yes" :native="true" />
BLADE,
        ],
    ],
];
