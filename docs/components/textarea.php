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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'key' => 'sizes',
            'title' => 'Sizes',
            'description' => '입력 목적에 맞춰 높이와 여백을 선택합니다.',
            'code' => <<<'BLADE'
<x-textarea size="sm" placeholder="Small" />
<x-textarea size="default" placeholder="Default" />
<x-textarea size="lg" placeholder="Large" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '오류와 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-textarea aria-invalid="true">잘못된 내용</x-textarea>
<x-textarea disabled>수정할 수 없는 내용</x-textarea>
BLADE,
        ],
        [
            'key' => 'auto-grow',
            'title' => 'Auto Grow',
            'description' => '내용에 따라 지정한 최대 행까지 자동으로 확장합니다.',
            'code' => <<<'BLADE'
<x-textarea name="memo" :rows="2" :max-rows="8" placeholder="내용에 따라 높이가 늘어납니다" />
BLADE,
        ],
    ],
];
