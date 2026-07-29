<?php

return [
    'title' => 'Input',
    'description' => '텍스트, 이메일, 비밀번호 등 여러 입력 유형을 일관된 크기와 상태 스타일로 표시합니다.',
    'parts' => [
        0 => 'input',
        1 => 'label',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'variant를 생략하면 flat 형태가 적용됩니다. 마우스를 올리면 배경만 자연스럽게 강조됩니다.',
            'code' => <<<'BLADE'
<x-label for="email" :required="true">이메일</x-label>
<x-input id="email" name="email" type="email" placeholder="name@example.com" :full-width="true" required />
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '화면의 강조 수준에 맞는 외형을 선택합니다.',
            'code' => <<<'BLADE'
<x-input variant="flat" placeholder="Flat" />
<x-input variant="faded" placeholder="Faded" />
<x-input variant="bordered" placeholder="Bordered" />
<x-input variant="light" placeholder="Light" />
<x-input variant="solid" placeholder="Solid" />
<x-input variant="ghost" placeholder="Ghost" />
<x-input variant="shadow" placeholder="Shadow" />
BLADE,
        ],
        [
            'key' => 'sizes',
            'title' => '크기',
            'description' => '폼 밀도에 맞춰 입력 높이를 선택합니다.',
            'code' => <<<'BLADE'
<x-input size="xs" placeholder="Extra Small" />
<x-input size="sm" placeholder="Small" />
<x-input size="md" placeholder="Medium" />
<x-input size="lg" placeholder="Large" />
<x-input size="xl" placeholder="Extra Large" />
BLADE,
        ],
        [
            'key' => 'colors',
            'title' => '색상',
            'description' => '평상시에는 의미 색상이 옅게 배경에 반영되고, 입력 중에는 같은 색상의 1px 테두리가 표시됩니다.',
            'code' => <<<'BLADE'
<x-input color="default" placeholder="Default" />
<x-input color="primary" placeholder="Primary" />
<x-input color="secondary" placeholder="Secondary" />
<x-input color="success" placeholder="Success" />
<x-input color="warning" placeholder="Warning" />
<x-input color="danger" placeholder="Danger" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '상태',
            'description' => '필수, 오류, 비활성 상태를 표시합니다.',
            'code' => <<<'BLADE'
<x-input name="required" required placeholder="필수 입력" />
<x-input name="invalid" aria-invalid="true" value="잘못된 값" />
<x-input name="disabled" disabled value="수정할 수 없음" />
BLADE,
        ],
        [
            'key' => 'mask',
            'title' => '입력 형식 제한',
            'description' => '별도 컴포넌트 없이 mask를 Input에 바로 지정합니다. 9는 숫자, a는 영문, A는 대문자, *는 영문·숫자입니다.',
            'code' => <<<'BLADE'
<x-input name="phone" mask="999-9999-9999" inputmode="numeric" placeholder="010-0000-0000" />
<x-input name="business_number" mask="999-99-99999" inputmode="numeric" placeholder="000-00-00000" />
<x-input name="product_code" mask="AAA-9999" placeholder="ABC-1234" />
BLADE,
        ],
        [
            'key' => 'password',
            'title' => '비밀번호 표시 전환',
            'description' => '비밀번호 표시 전환 버튼을 켜거나 끕니다.',
            'code' => <<<'BLADE'
<x-input type="password" name="password" placeholder="토글 사용" />
<x-input type="password" name="pin" :toggle="false" placeholder="토글 사용 안 함" />
BLADE,
        ],
        [
            'key' => 'full-width',
            'title' => '전체 너비',
            'description' => 'full-width가 true일 때만 부모 너비를 채웁니다.',
            'code' => <<<'BLADE'
<x-input name="search" :full-width="true" placeholder="전체 너비 입력" />
BLADE,
        ],
    ],
];
