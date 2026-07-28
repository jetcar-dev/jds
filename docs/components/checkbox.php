<?php

return [
    'title' => 'Checkbox',
    'description' => '여러 선택지 또는 동의 여부를 입력합니다.',
    'parts' => [
        0 => 'checkbox',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '라벨을 눌러도 선택 상태가 바뀌며 폼 제출 시 지정한 값이 전달됩니다.',
            'code' => <<<'BLADE'
<x-checkbox name="terms" value="1" label="이용약관에 동의합니다" :checked="true" />
BLADE,
        ],
        [
            'key' => 'states',
            'title' => '상태',
            'description' => '선택, 일부 선택, 비활성 상태를 비교합니다.',
            'code' => <<<'BLADE'
<x-checkbox :checked="true" aria-label="선택됨" />
<x-checkbox :indeterminate="true" aria-label="일부 선택" />
<x-checkbox :disabled="true" aria-label="비활성" />
BLADE,
        ],
        [
            'key' => 'native',
            'title' => '브라우저 기본 형태',
            'description' => 'JavaScript 없이 네이티브 체크박스로 사용합니다.',
            'code' => <<<'BLADE'
<x-checkbox name="agree" value="yes" :native="true" />
BLADE,
        ],
        [
            'key' => 'permissions',
            'title' => '권한 선택',
            'description' => '실제 폼에서는 관련 권한을 세로 목록으로 묶어 사용합니다.',
            'code' => <<<'BLADE'
<div class="jds-example-surface">
    <strong>직원 권한</strong>
    <x-checkbox name="permissions[]" value="orders" label="주문 관리" :checked="true" />
    <x-checkbox name="permissions[]" value="settlement" label="정산 조회" />
    <x-checkbox name="permissions[]" value="members" label="회원 관리" :disabled="true" />
</div>
BLADE,
        ],
    ],
];
