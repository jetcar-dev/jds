<?php

return [
    'title' => 'Button Group',
    'description' => '관련 버튼과 텍스트를 하나의 그룹으로 연결합니다.',
    'parts' => [
        0 => 'button-group',
        1 => 'button-group-text',
        2 => 'button-group-separator',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-button-group orientation="horizontal">
    <x-button variant="outline">이전</x-button>
    <x-button-group-separator />
    <x-button variant="outline">다음</x-button>
</x-button-group>
BLADE,
        ],
        [
            'key' => 'with-text',
            'title' => 'With Text',
            'description' => '고정 텍스트와 버튼을 하나의 컨트롤로 묶습니다.',
            'code' => <<<'BLADE'
<x-button-group>
    <x-button-group-text>₩</x-button-group-text>
    <x-button variant="outline">금액 입력</x-button>
</x-button-group>
BLADE,
        ],
        [
            'key' => 'vertical',
            'title' => 'Vertical',
            'description' => '관련 작업을 세로 방향으로 배치합니다.',
            'code' => <<<'BLADE'
<x-button-group orientation="vertical">
    <x-button variant="outline">프로필</x-button>
    <x-button variant="outline">설정</x-button>
    <x-button variant="outline">로그아웃</x-button>
</x-button-group>
BLADE,
        ],
    ],
];
