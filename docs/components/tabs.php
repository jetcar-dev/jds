<?php

return [
    'title' => 'Tabs',
    'description' => '관련된 내용을 탭으로 나누고 한 번에 하나의 패널을 표시합니다. 선택 표시는 항목 사이를 부드럽게 이동합니다.',
    'parts' => ['tabs', 'tabs-list', 'tabs-trigger', 'tabs-content'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '탭 버튼과 패널의 value를 같게 지정해 서로 연결합니다. 선택 표시와 패널 전환 효과는 자동으로 적용됩니다.',
            'code' => <<<'BLADE'
<x-tabs value="account">
    <x-tabs-list>
        <x-tabs-trigger value="account">계정</x-tabs-trigger>
        <x-tabs-trigger value="security">보안</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="account">
        <x-input name="email" type="email" placeholder="이메일" />
    </x-tabs-content>
    <x-tabs-content value="security">
        <x-button variant="outline">비밀번호 변경</x-button>
    </x-tabs-content>
</x-tabs>
BLADE,
        ],
        [
            'key' => 'structured',
            'title' => '밑줄과 전체 너비',
            'description' => 'appearance="line"은 선택한 탭 아래에 선을 표시하고, full-width는 전체 너비를 사용합니다.',
            'code' => <<<'BLADE'
<x-tabs value="profile" :full-width="true">
    <x-tabs-list appearance="line">
        <x-tabs-trigger value="profile">프로필</x-tabs-trigger>
        <x-tabs-trigger value="security">보안</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="profile">프로필 설정</x-tabs-content>
    <x-tabs-content value="security">보안 설정</x-tabs-content>
</x-tabs>
BLADE,
        ],
        [
            'key' => 'advanced',
            'title' => '둥근 탭과 비활성화',
            'description' => 'appearance="round"는 둥근 버튼 모양이며 disabled로 사용할 수 없는 탭을 표시합니다.',
            'code' => <<<'BLADE'
<x-tabs value="overview">
    <x-tabs-list appearance="round">
        <x-tabs-trigger value="overview">개요</x-tabs-trigger>
        <x-tabs-trigger value="history">이력</x-tabs-trigger>
        <x-tabs-trigger value="beta" disabled>베타</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="overview">개요</x-tabs-content>
    <x-tabs-content value="history">변경 이력</x-tabs-content>
    <x-tabs-content value="beta">베타 기능</x-tabs-content>
</x-tabs>
BLADE,
        ],
        [
            'key' => 'dashboard',
            'title' => '대시보드 화면 전환',
            'description' => '패널에는 카드, 표, 폼 등 구조가 다른 콘텐츠를 그대로 넣을 수 있습니다.',
            'code' => <<<'BLADE'
<x-tabs value="summary" :full-width="true">
    <x-tabs-list appearance="box">
        <x-tabs-trigger value="summary">요약</x-tabs-trigger>
        <x-tabs-trigger value="activity">최근 활동</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="summary">
        <div class="jds-example-grid">
            <x-card variant="secondary"><x-card-title>운행 차량</x-card-title><x-card-content>128대</x-card-content></x-card>
            <x-card variant="tertiary"><x-card-title>정비 예정</x-card-title><x-card-content>7대</x-card-content></x-card>
        </div>
    </x-tabs-content>
    <x-tabs-content value="activity">
        <x-card><x-card-content>차량 12가 3456의 배차가 완료되었습니다.</x-card-content></x-card>
    </x-tabs-content>
</x-tabs>
BLADE,
        ],
    ],
];
