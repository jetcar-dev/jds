<?php

return [
    'title' => 'JSON Viewer',
    'description' => '배열과 객체 데이터를 접고 펼칠 수 있는 트리로 표시합니다.',
    'parts' => [
        0 => 'json-viewer',
        1 => 'json-viewer-node',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '배열이나 객체를 value에 전달하면 자료형에 따라 색상이 구분된 트리로 표시됩니다.',
            'code' => <<<'BLADE'
<x-json-viewer
    root-label="응답 데이터"
    :expanded="true"
    :data="['status' => 'success', 'items' => [['id' => 1]]]"
/>
BLADE,
        ],
        [
            'key' => 'collapsed',
            'title' => '접힌 상태로 시작',
            'description' => '큰 데이터는 접힌 상태로 시작합니다.',
            'code' => <<<'BLADE'
<x-json-viewer root-label="API 응답" :expanded="false" :data="$response" />
BLADE,
        ],
        [
            'key' => 'types',
            'title' => '값 유형',
            'description' => '문자열, 숫자, boolean, null, 배열을 구분해 표시합니다.',
            'code' => <<<'BLADE'
<x-json-viewer :data="['name' => 'JetCar', 'count' => 12, 'active' => true, 'memo' => null, 'tags' => ['ERP', 'UI']]" />
BLADE,
        ],
        [
            'key' => 'nested-response',
            'title' => '중첩된 API 응답',
            'description' => '중첩된 객체와 배열도 레벨별로 접고 펼치며 전체 JSON을 복사할 수 있습니다.',
            'code' => <<<'BLADE'
<x-json-viewer root-label="배차 API" :expanded="true" :data="[
    'status' => 'success',
    'vehicle' => ['id' => 42, 'plate' => '12가 3456'],
    'driver' => ['name' => '김기사', 'available' => true],
    'stops' => [
        ['order' => 1, 'address' => '서울시 강남구'],
        ['order' => 2, 'address' => '서울시 송파구'],
    ],
]" />
BLADE,
        ],
    ],
];
