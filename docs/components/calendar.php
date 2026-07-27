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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'title' => 'Single Date',
            'description' => '하나의 날짜만 선택합니다.',
            'code' => <<<'BLADE'
<x-calendar name="due_date" value="2026-07-27" />
BLADE,
        ],
        [
            'key' => 'constraints',
            'title' => 'Date Constraints',
            'description' => '선택 가능 기간과 주 시작 요일을 제한합니다.',
            'code' => <<<'BLADE'
<x-calendar name="booking" min="2026-07-10" max="2026-07-31" default-month="2026-07-10" week-start="monday" :show-outside-days="false" />
BLADE,
        ],
    ],
];
