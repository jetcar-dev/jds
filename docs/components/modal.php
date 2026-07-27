<?php

return [
    'title' => 'Modal',
    'description' => '배경 위에 집중된 작업이나 확인 화면을 표시합니다.',
    'parts' => [
        0 => 'modal',
        1 => 'modal-trigger',
        2 => 'modal-content',
        3 => 'modal-header',
        4 => 'modal-title',
        5 => 'modal-description',
        6 => 'modal-footer',
        7 => 'modal-close',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
            'code' => <<<'BLADE'
<x-modal id="member-modal" backdrop-variant="blur" :is-dismissable="false">
    <x-modal-trigger><x-button>회원 추가</x-button></x-modal-trigger>
    <x-modal-content>
        <x-modal-header><x-modal-title>회원 추가</x-modal-title></x-modal-header>
        <x-modal-footer><x-modal-close><x-button variant="outline">취소</x-button></x-modal-close></x-modal-footer>
    </x-modal-content>
</x-modal>
BLADE,
        ],
        [
            'key' => 'backdrops',
            'title' => 'Backdrop Variants',
            'description' => 'opaque, blur, transparent 배경 효과를 선택합니다.',
            'code' => <<<'BLADE'
<x-modal backdrop-variant="blur"><x-modal-trigger><x-button>Blur Modal</x-button></x-modal-trigger><x-modal-content><x-modal-title>Blur 배경</x-modal-title></x-modal-content></x-modal>
BLADE,
        ],
        [
            'key' => 'dismiss',
            'title' => 'Dismiss Behavior',
            'description' => '배경 클릭과 Escape 닫기 동작을 제한합니다.',
            'code' => <<<'BLADE'
<x-modal :is-dismissable="false" :is-keyboard-dismiss-disabled="true"><x-modal-trigger><x-button variant="outline">필수 확인</x-button></x-modal-trigger><x-modal-content><x-modal-title>명시적으로 닫아야 합니다</x-modal-title><x-modal-close><x-button>확인</x-button></x-modal-close></x-modal-content></x-modal>
BLADE,
        ],
    ],
];
