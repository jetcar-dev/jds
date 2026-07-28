<?php

return [
    'title' => 'Group',
    'description' => '버튼과 입력처럼 서로 다른 요소를 하나의 둥근 영역에 연결해 단일 조작 요소처럼 표시합니다.',
    'parts' => ['group'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '여러 요소 묶기',
            'description' => '자식 요소의 개별 테두리와 그림자를 정리해 하나의 연결된 조작 영역으로 표시합니다.',
            'code' => <<<'BLADE'
<x-group :full-width="true">
    <x-combobox name="workspace" :options="$workspaces" placeholder="사업장 선택" />
    <x-button>이동</x-button>
</x-group>
BLADE,
        ],
        [
            'key' => 'search',
            'title' => '반응형 검색 영역',
            'description' => 'collapse를 사용하면 좁은 화면에서 세로로 배치됩니다.',
            'code' => <<<'BLADE'
<x-group :full-width="true" :collapse="true">
    <x-select :options="['name' => '고객명', 'phone' => '연락처']" value="name" />
    <x-input name="query" placeholder="검색어" :full-width="true" />
    <x-button>검색</x-button>
</x-group>
BLADE,
        ],
        [
            'key' => 'variants',
            'title' => '형태',
            'description' => '기본은 flat이며 강조 수준에 따라 outline, faded, ghost를 선택합니다.',
            'code' => <<<'BLADE'
<x-group variant="flat"><x-input placeholder="Flat" /><x-button>검색</x-button></x-group>
<x-group variant="outline"><x-input placeholder="Outline" /><x-button>검색</x-button></x-group>
<x-group variant="faded"><x-input placeholder="Faded" /><x-button>검색</x-button></x-group>
<x-group variant="ghost"><x-input placeholder="Ghost" /><x-button variant="ghost">검색</x-button></x-group>
BLADE,
        ],
        [
            'key' => 'colors-sizes',
            'title' => '색상과 크기',
            'description' => 'Group의 color와 size가 내부 결합 상태의 강조색과 높이를 함께 제어합니다.',
            'code' => <<<'BLADE'
<x-group color="primary" size="xs"><x-input placeholder="XS" /><x-button>검색</x-button></x-group>
<x-group color="success" size="sm"><x-input placeholder="SM" /><x-button>확인</x-button></x-group>
<x-group color="warning" size="md"><x-input placeholder="MD" /><x-button>확인</x-button></x-group>
<x-group color="danger" size="lg"><x-input placeholder="LG" /><x-button>삭제</x-button></x-group>
<x-group color="secondary" size="xl"><x-input placeholder="XL" /><x-button>검색</x-button></x-group>
BLADE,
        ],
        [
            'key' => 'selection',
            'title' => '선택 버튼 묶기',
            'description' => 'Toggle을 넣으면 단일 또는 다중 선택을 관리하고 name 값도 함께 제출합니다.',
            'code' => <<<'BLADE'
<x-group selection="single" name="align" value="left">
    <x-toggle value="left">왼쪽</x-toggle>
    <x-toggle value="center">가운데</x-toggle>
    <x-toggle value="right">오른쪽</x-toggle>
</x-group>
BLADE,
        ],
        [
            'key' => 'actions',
            'title' => '작업 버튼 묶기',
            'description' => '버튼만 넣으면 모서리와 간격이 자연스럽게 이어진 선택 영역이 됩니다. 자식의 variant는 선택된 항목의 강조 수준을 구분할 때 사용합니다.',
            'code' => <<<'BLADE'
<x-group variant="outline">
    <x-button variant="outline">이전</x-button>
    <x-button>오늘</x-button>
    <x-button variant="faded">다음</x-button>
</x-group>
BLADE,
        ],
    ],
];
