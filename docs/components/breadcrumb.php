<?php

return [
    'title' => 'Breadcrumb',
    'description' => '라벨과 주소 배열로 현재 이동 경로를 표시합니다.',
    'parts' => ['breadcrumb'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '마지막 항목은 자동으로 현재 페이지가 됩니다.',
            'code' => <<<'BLADE'
<x-breadcrumb :items="['홈' => '/', '회원' => '/members', '회원 상세']" />
BLADE,
        ],
        [
            'key' => 'separator',
            'title' => '구분 기호 바꾸기',
            'description' => 'separator 속성으로 구분 문자를 바꿉니다.',
            'code' => <<<'BLADE'
<x-breadcrumb :items="['문서' => '/docs', '설치']" separator="/" />
BLADE,
        ],
        [
            'key' => 'erp-path',
            'title' => 'ERP 화면 경로',
            'description' => '깊은 업무 화면에서는 상위 메뉴부터 현재 레코드까지 경로를 전달합니다.',
            'code' => <<<'BLADE'
<x-breadcrumb
    aria-label="차량 상세 경로"
    :items="[
        '대시보드' => '/',
        '차량 관리' => '/vehicles',
        '운행 차량' => '/vehicles/active',
        '12가 3456',
    ]"
/>
BLADE,
        ],
        [
            'key' => 'custom-content',
            'title' => '내용 자유롭게 구성하기',
            'description' => '배열 대신 하위 컴포넌트를 직접 조합하면 말줄임이나 아이콘 같은 특수 경로도 만들 수 있습니다.',
            'code' => <<<'BLADE'
<x-breadcrumb aria-label="사용자 지정 경로">
    <x-breadcrumb-list>
        <x-breadcrumb-item><x-breadcrumb-link href="/">홈</x-breadcrumb-link></x-breadcrumb-item>
        <x-breadcrumb-separator />
        <x-breadcrumb-item><x-breadcrumb-ellipsis /></x-breadcrumb-item>
        <x-breadcrumb-separator />
        <x-breadcrumb-item><x-breadcrumb-page>정산 상세</x-breadcrumb-page></x-breadcrumb-item>
    </x-breadcrumb-list>
</x-breadcrumb>
BLADE,
        ],
    ],
];
