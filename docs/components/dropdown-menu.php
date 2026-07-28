<?php

return [
    'title' => 'Dropdown Menu',
    'description' => '임의의 트리거와 메뉴 항목, 상태, 서브 메뉴를 자유롭게 조합합니다.',
    'parts' => [
        'dropdown-menu', 'dropdown-menu-trigger', 'dropdown-menu-content',
        'dropdown-menu-item', 'dropdown-menu-checkbox-item', 'dropdown-menu-radio-group',
        'dropdown-menu-radio-item', 'dropdown-menu-separator', 'dropdown-menu-sub',
        'dropdown-menu-sub-trigger', 'dropdown-menu-sub-content',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '트리거에는 원하는 버튼을 넣고, 메뉴 내용에는 필요한 항목을 순서대로 배치합니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu>
    <x-dropdown-menu-trigger><x-button variant="outline">메뉴</x-button></x-dropdown-menu-trigger>
    <x-dropdown-menu-content>
        <x-dropdown-menu-item href="/profile"><x-icon name="user-linear" /> 프로필</x-dropdown-menu-item>
        <x-dropdown-menu-item href="/settings">설정</x-dropdown-menu-item>
        <x-dropdown-menu-separator />
        <x-dropdown-menu-item color="danger">삭제</x-dropdown-menu-item>
    </x-dropdown-menu-content>
</x-dropdown-menu>
BLADE,
        ],
        [
            'key' => 'appearance',
            'title' => '메뉴 항목 꾸미기',
            'description' => '메뉴 항목도 공통 variant, color, size 값을 사용합니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu>
    <x-dropdown-menu-trigger><x-button variant="outline">상태</x-button></x-dropdown-menu-trigger>
    <x-dropdown-menu-content>
        <x-dropdown-menu-item variant="flat" color="default" size="xs">기본</x-dropdown-menu-item>
        <x-dropdown-menu-item variant="faded" color="success" size="sm">완료</x-dropdown-menu-item>
        <x-dropdown-menu-item variant="outline" color="warning" size="md">대기</x-dropdown-menu-item>
        <x-dropdown-menu-item variant="ghost" color="danger" size="lg">삭제</x-dropdown-menu-item>
    </x-dropdown-menu-content>
</x-dropdown-menu>
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '메뉴 항목 상태',
            'description' => '체크 항목과 라디오 항목은 각각 독립된 선택 상태를 가집니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu>
    <x-dropdown-menu-trigger><x-button variant="outline">보기</x-button></x-dropdown-menu-trigger>
    <x-dropdown-menu-content>
        <x-dropdown-menu-checkbox-item :checked="true">상태 표시</x-dropdown-menu-checkbox-item>
        <x-dropdown-menu-checkbox-item>담당자 표시</x-dropdown-menu-checkbox-item>
        <x-dropdown-menu-separator />
        <x-dropdown-menu-radio-group value="comfortable">
            <x-dropdown-menu-radio-item value="compact">좁게</x-dropdown-menu-radio-item>
            <x-dropdown-menu-radio-item value="comfortable">편안하게</x-dropdown-menu-radio-item>
        </x-dropdown-menu-radio-group>
    </x-dropdown-menu-content>
</x-dropdown-menu>
BLADE,
        ],
        [
            'key' => 'advanced',
            'title' => '하위 메뉴',
            'description' => '하위 메뉴 안에 트리거와 내용을 넣으면 마우스를 올리거나 키보드로 이동할 때 메뉴가 열립니다.',
            'code' => <<<'BLADE'
<x-dropdown-menu>
    <x-dropdown-menu-trigger><x-button variant="outline">공유</x-button></x-dropdown-menu-trigger>
    <x-dropdown-menu-content>
        <x-dropdown-menu-sub>
            <x-dropdown-menu-sub-trigger>보내기</x-dropdown-menu-sub-trigger>
            <x-dropdown-menu-sub-content>
                <x-dropdown-menu-item>링크 복사</x-dropdown-menu-item>
                <x-dropdown-menu-item>초대 보내기</x-dropdown-menu-item>
            </x-dropdown-menu-sub-content>
        </x-dropdown-menu-sub>
    </x-dropdown-menu-content>
</x-dropdown-menu>
BLADE,
        ],
    ],
];
