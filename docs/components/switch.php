<?php

return [
    'title' => 'Switch',
    'description' => '설정을 즉시 켜거나 끄는 이진 입력입니다.',
    'parts' => [
        0 => 'switch',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<label>
    <x-switch name="notifications" value="1" :checked="true" />
    알림 받기
</label>
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => 'Sizes',
            'description' => '폼 밀도에 맞춰 크기를 선택합니다.',
            'code' => <<<'BLADE'
<x-switch size="sm" aria-label="작게" />
<x-switch size="default" aria-label="기본" />
<x-switch size="lg" aria-label="크게" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '켜짐, 꺼짐, 비활성 상태를 비교합니다.',
            'code' => <<<'BLADE'
<x-switch :checked="true" aria-label="켜짐" />
<x-switch :checked="false" aria-label="꺼짐" />
<x-switch :checked="true" :disabled="true" aria-label="비활성" />
BLADE,
        ],
    ],
];
