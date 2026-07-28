<?php

return [
    'title' => 'Calendar',
    'description' => '단일 날짜와 날짜 범위를 달력에서 직접 선택합니다.',
    'parts' => [
        0 => 'calendar',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '값을 지정하지 않으면 오늘을 기준으로 한 달력을 표시합니다.',
            'code' => <<<'BLADE'
<x-calendar
    mode="range"
    name="period"
    :value="['from' => '2026-07-01', 'to' => '2026-07-07']"
    week-start="monday"
    :number-of-months="2"
/>
BLADE,
        ],
        [
            'key' => 'single',
            'title' => '날짜 하나 선택',
            'description' => '하나의 날짜만 선택합니다.',
            'code' => <<<'BLADE'
<x-calendar name="due_date" value="2026-07-27" />
BLADE,
        ],
        [
            'key' => 'constraints',
            'title' => '선택 가능한 날짜 제한',
            'description' => '선택 가능 기간과 주 시작 요일을 제한합니다.',
            'code' => <<<'BLADE'
<x-calendar name="booking" min="2026-07-10" max="2026-07-31" default-month="2026-07-10" week-start="monday" :show-outside-days="false" />
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => '여러 달 표시',
            'description' => 'mode="multiple"로 정기 휴무일이나 작업 예정일을 여러 개 선택합니다.',
            'code' => <<<'BLADE'
<x-calendar
    mode="multiple"
    name="closed_dates"
    :value="['2026-07-04', '2026-07-11', '2026-07-18']"
    default-month="2026-07-01"
    week-start="monday"
/>
BLADE,
        ],
    ],
];
