<?php

return [
    'title' => 'Input',
    'description' => '텍스트와 다양한 네이티브 입력 타입을 공통 스타일로 표시합니다.',
    'parts' => [
        0 => 'input',
        1 => 'label',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-label for="email" :required="true">이메일</x-label>
<x-input id="email" name="email" type="email" placeholder="name@example.com" :full-width="true" required />
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => 'Variants',
            'description' => '화면의 강조 수준에 맞는 외형을 선택합니다.',
            'code' => <<<'BLADE'
<x-input variant="outline" placeholder="Outline" />
<x-input variant="flat" placeholder="Flat" />
<x-input variant="underlined" placeholder="Underlined" />
<x-input variant="faded" placeholder="Faded" />
<x-input variant="ghost" placeholder="Ghost" />
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => 'Sizes',
            'description' => '폼 밀도에 맞춰 입력 높이를 선택합니다.',
            'code' => <<<'BLADE'
<x-input size="sm" placeholder="Small" />
<x-input size="default" placeholder="Default" />
<x-input size="lg" placeholder="Large" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => 'States',
            'description' => '필수, 오류, 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-input name="required" required placeholder="필수 입력" />
<x-input name="invalid" aria-invalid="true" value="잘못된 값" />
<x-input name="disabled" disabled value="수정할 수 없음" />
BLADE,
        ],
        [
            'key' => 'password',
            'title' => 'Password Toggle',
            'description' => '비밀번호 표시 전환 버튼을 켜거나 끕니다.',
            'code' => <<<'BLADE'
<x-input type="password" name="password" placeholder="토글 사용" />
<x-input type="password" name="pin" :toggle="false" placeholder="토글 사용 안 함" />
BLADE,
        ],
        [
            'key' => 'full-width',
            'title' => 'Full Width',
            'description' => 'full-width가 true일 때만 부모 너비를 채웁니다.',
            'code' => <<<'BLADE'
<x-input name="search" :full-width="true" placeholder="전체 너비 입력" />
BLADE,
        ],
    ],
];
