<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class DropdownTest extends TestCase
{
    public function test_dropdown_renders_heroui_menu_slots_and_states(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-dropdown placement="bottom-end" :offset="8">
    <x-dropdown-trigger><x-button>메뉴</x-button></x-dropdown-trigger>
    <x-dropdown-content variant="flat" color="secondary" selection-mode="multiple" :selected-keys="['copy']" :disabled-keys="['delete']">
        <x-slot:topContent>작업</x-slot:topContent>
        <x-dropdown-section title="파일" show-divider>
            <x-dropdown-item key="copy" description="링크를 복사합니다." shortcut="⌘C">
                <x-slot:startContent><x-icon name="solar:copy-linear" /></x-slot:startContent>
                복사
            </x-dropdown-item>
            <x-dropdown-item key="delete" color="danger">삭제</x-dropdown-item>
        </x-dropdown-section>
        <x-slot:bottomContent>끝</x-slot:bottomContent>
    </x-dropdown-content>
</x-dropdown>
BLADE);

        foreach ([
            'data-ui-component="dropdown"', 'data-slot="dropdown-trigger"', 'data-slot="dropdown-content"',
            'data-slot="list"', 'data-slot="dropdown-item"', 'data-slot="selected-icon"',
            'data-collection-item="true"', 'data-context="dropdown"', 'app-listbox-item',
            'data-slot="top-content"', 'data-slot="bottom-content"', 'data-slot="section"',
            'data-slot="heading"', 'data-slot="group"', 'data-selection-mode="multiple"',
            'data-variant="flat"', 'data-color="secondary"', 'data-key="copy"',
            'data-show-divider="true"', 'data-placement="bottom-end"',
        ] as $fragment) {
            $this->assertStringContainsString($fragment, $html);
        }
    }

    public function test_dropdown_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/dropdown.mdx'));

        foreach ([
            'dropdown-basic', 'dropdown-dynamic', 'dropdown-disabled', 'dropdown-variants',
            'dropdown-single-selection', 'dropdown-multiple-selection', 'dropdown-shortcuts',
            'dropdown-icons-description', 'dropdown-sections', 'dropdown-custom-trigger',
            'dropdown-edge-content',
        ] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
