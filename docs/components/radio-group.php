<?php

return [
    'title' => 'Radio Group',
    'description' => '선택지 배열을 전달해 라디오 그룹을 만듭니다.',
    'parts' => ['radio-group'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'value => label 배열만 전달합니다.',
            'code' => <<<'BLADE'
<x-radio-group name="plan" value="standard" :options="[
    'basic' => '기본',
    'standard' => '스탠다드',
]" />
BLADE,
        ],
        [
            'key' => 'descriptions',
            'title' => '설명과 함께 사용',
            'description' => '설명과 비활성 상태가 필요하면 항목만 배열로 확장합니다.',
            'code' => <<<'BLADE'
<x-radio-group name="delivery" :options="[
    'normal' => ['label' => '일반 배송', 'description' => '2~3일 소요'],
    'dawn' => ['label' => '새벽 배송', 'description' => '현재 준비 중', 'disabled' => true],
]" />
BLADE,
        ],
        [
            'key' => 'horizontal',
            'title' => '가로 배치',
            'description' => '한 줄 배치도 속성 하나로 변경합니다.',
            'code' => <<<'BLADE'
<x-radio-group name="status" orientation="horizontal" :options="['all' => '전체', 'active' => '활성']" />
BLADE,
        ],
        [
            'key' => 'business-options',
            'title' => '업무 옵션 선택',
            'description' => '업무 선택지는 라벨과 설명을 함께 제공해 차이를 바로 이해할 수 있게 합니다.',
            'code' => <<<'BLADE'
<div class="jds-example-surface" style="width: min(100%, 32rem);">
    <strong>정산 주기</strong>
    <x-radio-group name="settlement_cycle" value="weekly" :options="[
        'daily' => ['label' => '매일', 'description' => '매 영업일 자동 정산'],
        'weekly' => ['label' => '매주', 'description' => '매주 월요일 일괄 정산'],
        'monthly' => ['label' => '매월', 'description' => '매월 첫 영업일 정산'],
    ]" />
</div>
BLADE,
        ],
    ],
];
