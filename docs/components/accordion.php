<?php

return [
    'title' => 'Accordion',
    'description' => '접고 펼칠 수 있는 콘텐츠 영역을 구성합니다.',
    'parts' => [
        0 => 'accordion',
        1 => 'accordion-item',
        2 => 'accordion-trigger',
        3 => 'accordion-content',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-accordion type="single" collapsible value="shipping">
    <x-accordion-item value="shipping">
        <x-accordion-trigger>배송 정보</x-accordion-trigger>
        <x-accordion-content>영업일 기준 2~3일 내 출고됩니다.</x-accordion-content>
    </x-accordion-item>
</x-accordion>
BLADE,
        ],
        [
            'key' => 'multiple',
            'title' => 'Multiple',
            'description' => '여러 항목을 동시에 펼칩니다.',
            'code' => <<<'BLADE'
<x-accordion type="multiple" :value="['shipping', 'returns']">
    <x-accordion-item value="shipping"><x-accordion-trigger>배송</x-accordion-trigger><x-accordion-content>배송 안내입니다.</x-accordion-content></x-accordion-item>
    <x-accordion-item value="returns"><x-accordion-trigger>반품</x-accordion-trigger><x-accordion-content>반품 안내입니다.</x-accordion-content></x-accordion-item>
</x-accordion>
BLADE,
        ],
        [
            'key' => 'disabled',
            'title' => 'Disabled Item',
            'description' => '선택할 수 없는 항목을 함께 표시합니다.',
            'code' => <<<'BLADE'
<x-accordion type="single">
    <x-accordion-item value="available"><x-accordion-trigger>사용 가능</x-accordion-trigger><x-accordion-content>내용</x-accordion-content></x-accordion-item>
    <x-accordion-item value="unavailable"><x-accordion-trigger :disabled="true">사용 불가</x-accordion-trigger><x-accordion-content>내용</x-accordion-content></x-accordion-item>
</x-accordion>
BLADE,
        ],
    ],
];
