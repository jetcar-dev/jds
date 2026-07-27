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
            'title' => 'Default',
            'description' => '기본 버튼은 가장 중요한 작업에 사용합니다.',
            'code' => <<<'BLADE'
<x-button>Button</x-button>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => 'Variants',
            'description' => '작업의 중요도와 성격에 맞는 variant를 선택합니다.',
            'code' => <<<'BLADE'
<x-button variant="default">Default</x-button>
<x-button variant="secondary">Secondary</x-button>
<x-button variant="destructive">Destructive</x-button>
<x-button variant="outline">Outline</x-button>
<x-button variant="ghost">Ghost</x-button>
<x-button variant="link">Link</x-button>
BLADE,
        ],
        [
            'key' => 'with-icons',
            'title' => 'With Icons',
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
            'title' => 'Icon Only',
            'description' => '텍스트가 없는 버튼에는 접근성 이름을 반드시 제공합니다.',
            'code' => <<<'BLADE'
<x-button size="icon" aria-label="설정">
    <x-icon name="settings-linear" />
</x-button>

<x-button variant="outline" size="icon" aria-label="삭제">
    <x-icon name="trash-bin-trash-linear" />
</x-button>
BLADE,
        ],
        [
            'key' => 'loading',
            'title' => 'Loading',
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
            'title' => 'Sizes',
            'description' => '화면 밀도와 버튼의 중요도에 맞춰 크기를 선택합니다.',
            'code' => <<<'BLADE'
<x-button size="xs">Extra Small</x-button>
<x-button size="sm">Small</x-button>
<x-button size="default">Default</x-button>
<x-button size="lg">Large</x-button>
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => 'Disabled',
            'description' => '실행할 수 없는 작업은 disabled 상태로 표시합니다.',
            'code' => <<<'BLADE'
<x-button :disabled="true">Disabled</x-button>
<x-button variant="secondary" :disabled="true">Disabled</x-button>
<x-button variant="outline" :disabled="true">Disabled</x-button>
BLADE,
        ],
    ],
];
