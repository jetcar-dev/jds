<?php

return [
    'title' => 'Tabs',
    'description' => '동일한 영역 안에서 관련 콘텐츠 패널을 전환합니다.',
    'parts' => [
        0 => 'tabs',
        1 => 'tabs-list',
        2 => 'tabs-trigger',
        3 => 'tabs-content',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-tabs value="account">
    <x-tabs-list>
        <x-tabs-trigger value="account">계정</x-tabs-trigger>
        <x-tabs-trigger value="security">보안</x-tabs-trigger>
    </x-tabs-list>
    <x-tabs-content value="account">계정 설정</x-tabs-content>
    <x-tabs-content value="security">보안 설정</x-tabs-content>
</x-tabs>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => 'Variants',
            'description' => 'segmented, underline, pills 스타일을 선택합니다.',
            'code' => <<<'BLADE'
<x-tabs value="one"><x-tabs-list variant="pills"><x-tabs-trigger value="one">첫 번째</x-tabs-trigger><x-tabs-trigger value="two">두 번째</x-tabs-trigger></x-tabs-list><x-tabs-content value="one">첫 번째 내용</x-tabs-content><x-tabs-content value="two">두 번째 내용</x-tabs-content></x-tabs>
BLADE,
        ],
        [
            'key' => 'vertical',
            'title' => 'Vertical',
            'description' => '넓은 화면에서 탭과 콘텐츠를 세로 축으로 배치합니다.',
            'code' => <<<'BLADE'
<x-tabs value="profile" orientation="vertical"><x-tabs-list><x-tabs-trigger value="profile">프로필</x-tabs-trigger><x-tabs-trigger value="security">보안</x-tabs-trigger></x-tabs-list><x-tabs-content value="profile">프로필 설정</x-tabs-content><x-tabs-content value="security">보안 설정</x-tabs-content></x-tabs>
BLADE,
        ],
    ],
];
