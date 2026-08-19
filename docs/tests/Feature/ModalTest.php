<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class ModalTest extends TestCase
{
    public function test_modal_renders_heroui_structure_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-modal id="member-modal" size="2xl" radius="md" shadow="lg" backdrop="blur" placement="bottom" :dismissable="false" keyboard-dismiss-disabled scroll-behavior="outside" hide-close-button disable-animation default-open :should-block-scroll="false" portal-target="#portal" :motion="['options' => ['duration' => 250]]" draggable draggable-overflow>
    <x-modal-trigger><x-button>열기</x-button></x-modal-trigger>
    <x-modal-content>
        <x-modal-header>
            <x-modal-title>회원 선택</x-modal-title>
            <x-modal-description>회원을 선택해 주세요.</x-modal-description>
        </x-modal-header>
        <x-modal-body>본문</x-modal-body>
        <x-modal-footer><x-button data-modal-close>닫기</x-button></x-modal-footer>
    </x-modal-content>
</x-modal>
BLADE);

        foreach ([
            'id="member-modal"', 'data-slot="modal"', 'data-slot="modal-trigger"',
            'data-slot="wrapper"', 'data-slot="backdrop"', 'data-slot="modal-content"',
            'data-slot="close-button"', 'data-slot="modal-header"', 'data-slot="modal-title"',
            'data-slot="modal-description"', 'data-slot="modal-body"', 'data-slot="modal-footer"',
            'data-size="2xl"', 'data-radius="md"', 'data-shadow="lg"', 'data-backdrop="blur"',
            'data-placement="bottom"', 'data-dismissable="false"',
            'data-keyboard-dismiss-disabled="true"', 'data-scroll-behavior="outside"',
            'data-hide-close-button="true"', 'data-disable-animation="true"',
            'data-default-open="true"', 'data-should-block-scroll="false"',
            'data-portal-target="#portal"', 'data-motion=', 'data-draggable="true"',
            'data-draggable-overflow="true"',
            'role="dialog"', 'aria-modal="true"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_modal_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/modal.mdx'));

        foreach ([
            'modal-basic', 'modal-sizes', 'modal-non-dismissible', 'modal-placement',
            'modal-scroll', 'modal-backdrop', 'modal-form', 'modal-external-triggers',
            'modal-custom-close', 'modal-custom-backdrop', 'modal-custom-motion', 'modal-draggable',
            'modal-custom-styles',
        ] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
