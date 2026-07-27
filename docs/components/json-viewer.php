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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'title' => 'Collapsed',
            'description' => '큰 데이터는 접힌 상태로 시작합니다.',
            'code' => <<<'BLADE'
<x-json-viewer root-label="API 응답" :expanded="false" :data="$response" />
BLADE,
        ],
        [
            'key' => 'types',
            'title' => 'Value Types',
            'description' => '문자열, 숫자, boolean, null, 배열을 구분해 표시합니다.',
            'code' => <<<'BLADE'
<x-json-viewer :data="['name' => 'JetCar', 'count' => 12, 'active' => true, 'memo' => null, 'tags' => ['ERP', 'UI']]" />
BLADE,
        ],
    ],
];
