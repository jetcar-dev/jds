<?php

return [
    'title' => 'Field',
    'description' => '라벨, 설명, 입력 요소, 오류 메시지를 일관된 폼 필드로 묶습니다.',
    'parts' => [
        0 => 'field',
        1 => 'field-label',
        2 => 'field-content',
        3 => 'field-description',
        4 => 'field-error',
        5 => 'field-group',
        6 => 'field-set',
        7 => 'field-legend',
        8 => 'field-separator',
        9 => 'field-title',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-field :invalid="$errors->has('email')">
    <x-field-label for="email">이메일</x-field-label>
    <x-field-content>
        <x-input id="email" name="email" />
        <x-field-description>업무용 이메일을 입력하세요.</x-field-description>
        <x-field-error :messages="$errors->get('email')" />
    </x-field-content>
</x-field>
BLADE,
        ],
        [
            'key' => 'horizontal',
            'title' => 'Horizontal',
            'description' => '라벨과 입력 영역을 가로로 배치합니다.',
            'code' => <<<'BLADE'
<x-field orientation="horizontal">
    <x-field-label for="company">회사명</x-field-label>
    <x-field-content><x-input id="company" name="company" /><x-field-description>사업자등록증의 명칭을 입력하세요.</x-field-description></x-field-content>
</x-field>
BLADE,
        ],
        [
            'key' => 'invalid',
            'title' => 'Invalid',
            'description' => '검증 오류와 메시지를 함께 표시합니다.',
            'code' => <<<'BLADE'
<x-field :invalid="true"><x-field-label for="email">이메일</x-field-label><x-field-content>
    <x-input id="email" name="email" value="wrong" aria-invalid="true" />
    <x-field-error :messages="['올바른 이메일 주소를 입력하세요.']" />
</x-field-content></x-field>
BLADE,
        ],
    ],
];
