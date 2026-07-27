<?php

return [
    'title' => 'Dropdown Menu',
    'description' => '트리거에서 작업 메뉴와 중첩 메뉴를 엽니다.',
    'parts' => [
        0 => 'dropdown-menu',
        1 => 'dropdown-menu-trigger',
        2 => 'dropdown-menu-content',
        3 => 'dropdown-menu-group',
        4 => 'dropdown-menu-label',
        5 => 'dropdown-menu-item',
        6 => 'dropdown-menu-checkbox-item',
        7 => 'dropdown-menu-radio-group',
        8 => 'dropdown-menu-radio-item',
        9 => 'dropdown-menu-separator',
        10 => 'dropdown-menu-shortcut',
        11 => 'dropdown-menu-sub',
        12 => 'dropdown-menu-sub-trigger',
        13 => 'dropdown-menu-sub-content',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu>
    <x-dropdown-menu-trigger><x-button variant="outline">메뉴</x-button></x-dropdown-menu-trigger>
    <x-dropdown-menu-content align="start" side="bottom">
        <x-dropdown-menu-item>수정</x-dropdown-menu-item>
        <x-dropdown-menu-sub>
            <x-dropdown-menu-sub-trigger>공유</x-dropdown-menu-sub-trigger>
            <x-dropdown-menu-sub-content><x-dropdown-menu-item>링크 복사</x-dropdown-menu-item></x-dropdown-menu-sub-content>
        </x-dropdown-menu-sub>
    </x-dropdown-menu-content>
</x-dropdown-menu>
BLADE,
        ],
        [
            'key' => 'checkbox',
            'title' => 'Checkbox Items',
            'description' => '메뉴 안에서 여러 설정을 켜고 끕니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu><x-dropdown-menu-trigger><x-button variant="outline">보기</x-button></x-dropdown-menu-trigger><x-dropdown-menu-content>
    <x-dropdown-menu-label>표시 항목</x-dropdown-menu-label>
    <x-dropdown-menu-checkbox-item :checked="true">상태</x-dropdown-menu-checkbox-item>
    <x-dropdown-menu-checkbox-item>담당자</x-dropdown-menu-checkbox-item>
</x-dropdown-menu-content></x-dropdown-menu>
BLADE,
        ],
        [
            'key' => 'radio',
            'title' => 'Radio Items',
            'description' => '메뉴 안에서 하나의 옵션을 선택합니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu><x-dropdown-menu-trigger><x-button variant="outline">정렬</x-button></x-dropdown-menu-trigger><x-dropdown-menu-content>
    <x-dropdown-menu-radio-group value="latest"><x-dropdown-menu-radio-item value="latest">최신순</x-dropdown-menu-radio-item><x-dropdown-menu-radio-item value="oldest">오래된순</x-dropdown-menu-radio-item></x-dropdown-menu-radio-group>
</x-dropdown-menu-content></x-dropdown-menu>
BLADE,
        ],
    ],
];
