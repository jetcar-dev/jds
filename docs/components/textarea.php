<?php

return [
    'title' => 'Textarea',
    'description' => '여러 줄 텍스트를 입력하며 최대 자동 확장 행 수를 설정할 수 있습니다.',
    'parts' => [
        0 => 'textarea',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'name, placeholder, rows를 지정해 여러 줄의 내용을 입력받습니다.',
            'code' => <<<'BLADE'
<x-textarea
    name="memo"
    :rows="3"
    :max-rows="8"
    placeholder="메모를 입력해 주세요"
/>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '입력 계열과 동일한 네 가지 variant를 사용합니다.',
            'code' => <<<'BLADE'
<x-textarea variant="flat" placeholder="Flat" />
<x-textarea variant="outline" placeholder="Outline" />
<x-textarea variant="faded" placeholder="Faded" />
<x-textarea variant="ghost" placeholder="Ghost" />
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => '크기',
            'description' => '입력 목적에 맞춰 높이와 여백을 선택합니다.',
            'code' => <<<'BLADE'
<x-textarea size="xs" placeholder="Extra Small" />
<x-textarea size="sm" placeholder="Small" />
<x-textarea size="md" placeholder="Medium" />
<x-textarea size="lg" placeholder="Large" />
<x-textarea size="xl" placeholder="Extra Large" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '상태',
            'description' => '오류와 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-textarea aria-invalid="true">잘못된 내용</x-textarea>
<x-textarea disabled>수정할 수 없는 내용</x-textarea>
BLADE,
        ],
        [
            'key' => 'auto-grow',
            'title' => '내용에 맞춰 높이 늘리기',
            'description' => '내용에 따라 지정한 최대 행까지 자동으로 확장합니다.',
            'code' => <<<'BLADE'
<x-textarea name="memo" :rows="2" :max-rows="8" placeholder="내용에 따라 높이가 늘어납니다" />
BLADE,
        ],
        [
            'key' => 'full-width',
            'title' => '전체 너비',
            'description' => '부모 영역의 너비를 모두 사용하는 여러 줄 입력입니다.',
            'code' => <<<'BLADE'
<x-textarea name="memo" :full-width="true" placeholder="내용을 입력해 주세요" />
BLADE,
        ],
    ],
];
