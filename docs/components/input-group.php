<?php

return [
    'title' => 'Input Group',
    'description' => '입력 앞뒤에 텍스트, 아이콘, 버튼을 결합합니다.',
    'parts' => [
        0 => 'input-group',
        1 => 'input-group-input',
        2 => 'input-group-addon',
        3 => 'input-group-text',
        4 => 'input-group-button',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-input-group>
    <x-input-group-addon>https://</x-input-group-addon>
    <x-input-group-input name="domain" placeholder="example.com" />
    <x-input-group-button>확인</x-input-group-button>
</x-input-group>
BLADE,
        ],
        [
            'key' => 'icons',
            'title' => 'With Icons',
            'description' => '입력 앞뒤에 의미 있는 아이콘을 배치합니다.',
            'code' => <<<'BLADE'
<x-input-group><x-input-group-addon><x-icon name="magnifer-linear" /></x-input-group-addon><x-input-group-input placeholder="검색" /></x-input-group>
BLADE,
        ],
        [
            'key' => 'button',
            'title' => 'With Button',
            'description' => '입력값과 연결된 작업 버튼을 결합합니다.',
            'code' => <<<'BLADE'
<x-input-group><x-input-group-input name="coupon" placeholder="쿠폰 코드" /><x-input-group-button>적용</x-input-group-button></x-input-group>
BLADE,
        ],
    ],
];
