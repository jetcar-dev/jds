<?php

return [
    'title' => 'Copy Button',
    'description' => '지정한 값을 클립보드에 복사합니다.',
    'parts' => [
        0 => 'copy-button',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'value에 전달한 문자열을 클립보드에 복사하고 완료 상태를 알려 줍니다.',
            'code' => <<<'BLADE'
<x-copy-button value="JETCAR-ERP-2026" label="코드 복사">
    코드 복사
</x-copy-button>
BLADE,
        ],
        [
            'key' => 'icon-only',
            'title' => '아이콘 버튼',
            'description' => '좁은 영역에서는 아이콘만 표시합니다.',
            'code' => <<<'BLADE'
<x-copy-button value="ORD-2026-001" label="주문번호 복사" />
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => '비활성 상태',
            'description' => '복사할 수 없는 값은 비활성화합니다.',
            'code' => <<<'BLADE'
<x-copy-button value="" label="복사 불가" :disabled="true">복사</x-copy-button>
BLADE,
        ],
        [
            'key' => 'inline-value',
            'title' => '값과 함께 배치',
            'description' => '주문번호나 API 키처럼 읽기 전용 값을 보여 주는 영역에 복사 버튼을 함께 배치합니다.',
            'code' => <<<'BLADE'
<div class="jds-example-between jds-example-surface" style="max-width: 28rem;">
    <code>ORD-2026-000184</code>
    <x-copy-button value="ORD-2026-000184" label="주문번호 복사" />
</div>
BLADE,
        ],
    ],
];
