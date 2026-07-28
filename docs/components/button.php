<?php

return [
    'title' => 'Button',
    'description' => '작업 실행, 링크 이동, 아이콘 액션에 사용하는 버튼입니다.',
    'parts' => [
        0 => 'button',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '기본값은 flat, default, md입니다.',
            'code' => <<<'BLADE'
<x-button>Button</x-button>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '작업의 중요도와 성격에 맞는 variant를 선택합니다.',
            'code' => <<<'BLADE'
<x-button variant="flat">Flat</x-button>
<x-button variant="outline">Outline</x-button>
<x-button variant="faded">Faded</x-button>
<x-button variant="ghost">Ghost</x-button>
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => '색상',
            'description' => 'variant와 별개로 의미 색상을 선택합니다.',
            'code' => <<<'BLADE'
<x-button color="default">Default</x-button>
<x-button color="primary">Primary</x-button>
<x-button color="secondary">Secondary</x-button>
<x-button color="success">Success</x-button>
<x-button color="warning">Warning</x-button>
<x-button color="danger">Danger</x-button>
BLADE,
        ],
        [
            'key' => 'with-icons',
            'title' => '아이콘과 함께 사용',
            'description' => '버튼 텍스트 앞이나 뒤에 Solar 아이콘을 배치할 수 있습니다.',
            'code' => <<<'BLADE'
<x-button>
    <x-icon name="diskette-linear" />
    저장
</x-button>

<x-button variant="outline">
    다운로드
    <x-slot:after><x-icon name="download-minimalistic-linear" /></x-slot:after>
</x-button>
BLADE,
        ],
        [
            'key' => 'icon-only',
            'title' => '아이콘 버튼',
            'description' => '텍스트가 없는 버튼에는 접근성 이름을 반드시 제공합니다.',
            'code' => <<<'BLADE'
<x-button :icon-only="true" aria-label="설정">
    <x-icon name="settings-linear" />
</x-button>

<x-button variant="outline" color="danger" :icon-only="true" aria-label="삭제">
    <x-icon name="trash-bin-trash-linear" />
</x-button>
BLADE,
        ],
        [
            'key' => 'loading',
            'title' => '처리 중 상태',
            'description' => '진행 중에는 버튼을 비활성화하고 현재 상태를 알립니다.',
            'code' => <<<'BLADE'
<x-button :disabled="true" aria-busy="true">
    <span class="app-ui-spinner" aria-hidden="true"></span>
    저장 중
</x-button>
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => '크기',
            'description' => '화면 밀도와 버튼의 중요도에 맞춰 크기를 선택합니다.',
            'code' => <<<'BLADE'
<x-button size="xs">Extra Small</x-button>
<x-button size="sm">Small</x-button>
<x-button size="md">Medium</x-button>
<x-button size="lg">Large</x-button>
<x-button size="xl">Extra Large</x-button>
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => '비활성 상태',
            'description' => '실행할 수 없는 작업은 disabled 상태로 표시합니다.',
            'code' => <<<'BLADE'
<x-button :disabled="true">Disabled</x-button>
<x-button variant="faded" color="secondary" :disabled="true">Disabled</x-button>
<x-button variant="outline" color="danger" :disabled="true">Disabled</x-button>
BLADE,
        ],
    ],
];
