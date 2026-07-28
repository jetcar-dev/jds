<?php

return [
    'title' => 'Field',
    'description' => '라벨, 입력 요소, 도움말과 오류 메시지를 의미 있는 순서로 배치하는 폼 레이아웃입니다.',
    'parts' => ['field', 'label', 'field-description', 'field-error'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '공통 x-label을 직접 사용하므로 라벨 안에 필요한 내용을 자유롭게 넣을 수 있습니다.',
            'code' => <<<'BLADE'
<x-field>
    <x-label for="email" :required="true">이메일</x-label>
    <x-input id="email" name="email" type="email" :full-width="true" />
    <x-field-description>업무용 이메일을 입력하세요.</x-field-description>
</x-field>
BLADE,
        ],
        [
            'key' => 'content',
            'title' => '설명이 있는 라벨',
            'description' => '라벨도 슬롯이므로 보조 링크나 배지를 함께 배치할 수 있습니다.',
            'code' => <<<'BLADE'
<x-field>
    <x-label for="company">회사명 <x-badge size="sm">사업자</x-badge></x-label>
    <x-input id="company" name="company" placeholder="주식회사 제트카" :full-width="true" />
</x-field>
BLADE,
        ],
        [
            'key' => 'invalid',
            'title' => '오류 상태',
            'description' => '필드와 입력 요소에 오류 상태를 표시하고 오류 영역에 서버 검증 메시지를 전달합니다.',
            'code' => <<<'BLADE'
<x-field :invalid="true">
    <x-label for="invalid-email">이메일</x-label>
    <x-input id="invalid-email" value="wrong" aria-invalid="true" :full-width="true" />
    <x-field-error :messages="$errors->get('email')" />
</x-field>
BLADE,
        ],
        [
            'key' => 'horizontal',
            'title' => '가로 배치',
            'description' => 'orientation으로 라벨과 입력을 가로 배치합니다.',
            'code' => <<<'BLADE'
<x-field orientation="horizontal">
    <x-label for="manager">담당자</x-label>
    <x-field-content>
        <x-input id="manager" name="manager" :full-width="true" />
        <x-field-description>계약 담당자를 입력하세요.</x-field-description>
    </x-field-content>
</x-field>
BLADE,
        ],
    ],
];
