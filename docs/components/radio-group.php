<?php

return [
    'title' => 'Radio Group',
    'description' => '여러 선택지 중 하나의 값만 선택합니다.',
    'parts' => [
        0 => 'radio-group',
        1 => 'radio-group-item',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-radio-group name="plan" value="standard">
    <label><x-radio-group-item value="basic" /> 기본</label>
    <label><x-radio-group-item value="standard" /> 스탠다드</label>
</x-radio-group>
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => 'Disabled Option',
            'description' => '선택할 수 없는 옵션을 함께 표시합니다.',
            'code' => <<<'BLADE'
<x-radio-group name="delivery" value="normal"><label><x-radio-group-item value="normal" /> 일반 배송</label><label><x-radio-group-item value="dawn" :disabled="true" /> 새벽 배송</label></x-radio-group>
BLADE,
        ],
        [
            'key' => 'cards',
            'title' => 'With Descriptions',
            'description' => '선택지에 설명을 함께 제공하는 구성입니다.',
            'code' => <<<'BLADE'
<x-radio-group name="plan"><label><x-radio-group-item value="basic" /> 기본 요금제 — 개인용</label><label><x-radio-group-item value="team" /> 팀 요금제 — 협업용</label></x-radio-group>
BLADE,
        ],
    ],
];
