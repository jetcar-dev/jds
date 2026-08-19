<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ListboxTest extends TestCase
{
    public function test_listbox_renders_heroui_structure_and_selection_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-listbox name="status" selection-mode="multiple" :value="['ready']" :disabled-keys="['done']" label="상태">
                <x-listbox-item value="ready" description="준비 상태" shortcut="R">준비</x-listbox-item>
                <x-listbox-item value="done">완료</x-listbox-item>
            </x-listbox>
        BLADE);

        $this->assertStringContainsString('data-ui-component="listbox"', $html);
        $this->assertStringContainsString('data-slot="base"', $html);
        $this->assertStringContainsString('data-slot="list"', $html);
        $this->assertStringContainsString('role="listbox"', $html);
        $this->assertStringContainsString('aria-multiselectable="true"', $html);
        $this->assertStringContainsString('data-slot="wrapper"', $html);
        $this->assertStringContainsString('data-slot="description"', $html);
        $this->assertStringContainsString('data-slot="shortcut"', $html);
        $this->assertStringContainsString('data-ui-component="kbd"', $html);
        $this->assertStringContainsString('data-slot="selected-icon"', $html);
        $this->assertStringContainsString('name="status[]"', $html);
    }

    public function test_listbox_sections_and_content_slots_render(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-listbox label="메뉴">
                <x-slot:topContent>위</x-slot:topContent>
                <x-listbox-section title="작업" show-divider>
                    <x-listbox-item value="new">새 파일</x-listbox-item>
                </x-listbox-section>
                <x-slot:bottomContent>아래</x-slot:bottomContent>
            </x-listbox>
        BLADE);

        $this->assertStringContainsString('data-slot="top-content"', $html);
        $this->assertStringContainsString('data-slot="bottom-content"', $html);
        $this->assertStringContainsString('data-slot="section"', $html);
        $this->assertStringContainsString('data-slot="heading"', $html);
        $this->assertStringContainsString('role="group"', $html);
    }

    public function test_listbox_document_contains_complete_examples(): void
    {
        $content = file_get_contents(base_path('content/components/listbox.mdx'));

        foreach ([
            'listbox-basic', 'listbox-dynamic', 'listbox-disabled', 'listbox-variants',
            'data-listbox-demo-control="variant"', 'data-listbox-demo-control="color"',
            'listbox-single', 'listbox-multiple', 'listbox-icons',
            'listbox-description', 'listbox-content', 'listbox-sections', 'listbox-links',
            'listbox-empty', 'listbox-scroll', 'listbox-form',
        ] as $example) {
            $this->assertStringContainsString($example, $content);
        }
    }
}
