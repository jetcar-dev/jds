<?php

return [
    'title' => 'Date & Time',
    'description' => '날짜, 날짜 범위, 날짜와 시간을 팝오버에서 입력합니다.',
    'parts' => [
        0 => 'date-picker',
        1 => 'datetime-picker',
        2 => 'time-field',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '날짜 선택',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-date-picker
    mode="range"
    name="dates"
    :value="['from' => '2026-06-09', 'to' => '2026-06-26']"
    :presets="true"
/>
<x-datetime-picker name="starts_at" value="2026-06-09T09:30" />
<x-time-field name="work_time" value="09:30" hour-cycle="24" />
BLADE,
        ],
        [
            'key' => 'range',
            'title' => '기간 선택',
            'description' => '시작일과 종료일을 한 팝오버에서 선택합니다.',
            'code' => <<<'BLADE'
<x-date-picker mode="range" name="dates" :value="['from' => '2026-06-09', 'to' => '2026-06-26']" :presets="true" />
BLADE,
        ],
        [
            'key' => 'datetime',
            'title' => '날짜와 시간 선택',
            'description' => '날짜와 시간을 하나의 필드에서 입력합니다.',
            'code' => <<<'BLADE'
<x-datetime-picker name="starts_at" value="2026-06-09T09:30" hour-cycle="24" :minute-step="10" />
BLADE,
        ],
        [
            'key' => 'time',
            'title' => '시간 입력',
            'description' => '시간만 입력하고 초 또는 간격을 설정합니다.',
            'code' => <<<'BLADE'
<x-time-field name="work_time" value="09:30:00" hour-cycle="24" :seconds="true" :minute-step="5" />
BLADE,
        ],
    ],
];
