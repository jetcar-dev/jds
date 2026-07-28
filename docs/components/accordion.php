<?php

return [
    'title' => 'Accordion',
    'description' => '관련된 내용을 접었다 펼칠 수 있는 영역으로 정리합니다. 본문 높이에 맞춘 전환 효과와 아이콘 움직임이 자동으로 적용됩니다.',
    'parts' => ['accordion', 'accordion-item', 'accordion-trigger', 'accordion-content'],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '각 항목에 제목과 본문을 넣어 구성합니다. 본문에는 HTML과 다른 JDS 컴포넌트를 자유롭게 배치할 수 있습니다.',
            'code' => <<<'BLADE'
<x-accordion value="shipping" :collapsible="true">
    <x-accordion-item value="shipping">
        <x-accordion-trigger>배송 정보</x-accordion-trigger>
        <x-accordion-content>
            <p>영업일 기준 2~3일 내 출고됩니다.</p>
            <x-link href="/shipping">배송 정책 보기</x-link>
        </x-accordion-content>
    </x-accordion-item>
    <x-accordion-item value="returns">
        <x-accordion-trigger>반품 안내</x-accordion-trigger>
        <x-accordion-content>수령 후 7일 이내 신청할 수 있습니다.</x-accordion-content>
    </x-accordion-item>
</x-accordion>
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => '여러 항목 펼치기',
            'description' => 'type="multiple"을 사용하면 여러 영역을 동시에 열 수 있습니다. value 배열로 처음 열어 둘 항목을 지정합니다.',
            'code' => <<<'BLADE'
<x-accordion type="multiple" :value="['profile', 'security']">
    <x-accordion-item value="profile">
        <x-accordion-trigger>프로필</x-accordion-trigger>
        <x-accordion-content><x-input name="name" placeholder="이름" /></x-accordion-content>
    </x-accordion-item>
    <x-accordion-item value="security">
        <x-accordion-trigger>보안</x-accordion-trigger>
        <x-accordion-content><x-input type="password" name="password" /></x-accordion-content>
    </x-accordion-item>
</x-accordion>
BLADE,
        ],
        [
            'key' => 'advanced',
            'title' => '비활성 상태와 아이콘',
            'description' => '제목 영역에서 아이콘의 형태와 위치, 비활성 상태를 지정할 수 있습니다. 열림 상태에 따라 아이콘이 자연스럽게 전환됩니다.',
            'code' => <<<'BLADE'
<x-accordion>
    <x-accordion-item value="faq">
        <x-accordion-trigger icon="plus-minus">자주 묻는 질문</x-accordion-trigger>
        <x-accordion-content>답변 내용</x-accordion-content>
    </x-accordion-item>
    <x-accordion-item value="locked">
        <x-accordion-trigger disabled>준비 중</x-accordion-trigger>
        <x-accordion-content>준비 중인 내용</x-accordion-content>
    </x-accordion-item>
</x-accordion>
BLADE,
        ],
        [
            'key' => 'form-sections',
            'title' => '입력 화면 나누기',
            'description' => '긴 등록 화면을 의미 있는 구역으로 나누고 각 본문 안에 필요한 입력 요소를 배치합니다.',
            'code' => <<<'BLADE'
<x-accordion value="vehicle">
    <x-accordion-item value="vehicle">
        <x-accordion-trigger>차량 기본 정보</x-accordion-trigger>
        <x-accordion-content>
            <div class="jds-example-grid">
                <x-field><x-label for="plate">차량번호</x-label><x-input id="plate" name="plate" :full-width="true" /></x-field>
                <x-field><x-label for="model">차종</x-label><x-input id="model" name="model" :full-width="true" /></x-field>
            </div>
        </x-accordion-content>
    </x-accordion-item>
    <x-accordion-item value="owner">
        <x-accordion-trigger>소유자 정보</x-accordion-trigger>
        <x-accordion-content><x-input name="owner" placeholder="소유자명" :full-width="true" /></x-accordion-content>
    </x-accordion-item>
</x-accordion>
BLADE,
        ],
    ],
];
