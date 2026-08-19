<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

final class SelectTest extends TestCase
{
    public function test_select_renders_heroui_slots_and_form_contract(): void
    {
        $html = Blade::render(<<<'BLADE'
<x-select id="team" name="teams" label="팀" placeholder="선택" selection-mode="multiple" :value="['sales']" variant="bordered" color="primary" size="lg" radius="full" label-placement="outside" :disabled-keys="['support']" required invalid error-message="선택하세요" clearable>
    <x-slot:startContent>앞</x-slot:startContent>
    <x-slot:selectorIcon>열기</x-slot:selectorIcon>
    <x-select-section title="부서">
        <x-select-item value="sales" description="영업 부서"><x-slot:startContent>S</x-slot:startContent>영업팀<x-slot:endContent>01</x-slot:endContent></x-select-item>
        <x-select-item value="support">지원팀</x-select-item>
    </x-select-section>
</x-select>
BLADE);

        foreach (['base', 'mainWrapper', 'trigger', 'innerWrapper', 'value', 'clear-button', 'selectorIcon', 'popoverContent', 'listboxWrapper', 'listbox', 'listbox-section', 'listbox-item', 'textWrapper', 'selectedIcon', 'error-message'] as $slot) {
            $this->assertStringContainsString('data-slot="'.$slot.'"', $html);
        }
        $this->assertStringContainsString('data-selection-mode="multiple"', $html);
        $this->assertStringContainsString('data-variant="bordered"', $html);
        $this->assertStringContainsString('data-color="primary"', $html);
        $this->assertStringContainsString('data-size="lg"', $html);
        $this->assertStringContainsString('data-radius="full"', $html);
        $this->assertStringContainsString('data-invalid="true"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('name="teams[]"', $html);
        $this->assertStringContainsString('value="sales"', $html);
        $this->assertStringContainsString('앞', $html);
        $this->assertStringContainsString('열기', $html);
    }

    public function test_select_boolean_and_collection_options_render(): void
    {
        $html = Blade::render('<x-select disabled disallow-empty-selection disable-selector-icon-rotation disable-animation :show-scroll-indicators="false" virtualized :max-listbox-height="200" :item-height="40" :full-width="false"><x-select-item value="one" disabled>하나</x-select-item></x-select>');

        $this->assertStringContainsString('data-disabled="true"', $html);
        $this->assertStringContainsString('data-disallow-empty-selection="true"', $html);
        $this->assertStringContainsString('data-disable-selector-icon-rotation="true"', $html);
        $this->assertStringContainsString('data-disable-animation="true"', $html);
        $this->assertStringContainsString('data-show-scroll-indicators="false"', $html);
        $this->assertStringContainsString('data-virtualized="true"', $html);
        $this->assertStringContainsString('data-max-listbox-height="200"', $html);
        $this->assertStringContainsString('data-item-height="40"', $html);
        $this->assertStringContainsString('data-full-width="false"', $html);
    }

    public function test_select_document_contains_complete_examples(): void
    {
        $this->get('/components/select')
            ->assertOk()
            ->assertSee('select-basic')
            ->assertSee('select-multiple')
            ->assertSee('select-disabled')
            ->assertSee('select-sizes')
            ->assertSee('select-colors')
            ->assertSee('select-variants')
            ->assertSee('select-label-placement')
            ->assertSee('select-without-placeholder')
            ->assertSee('select-with-placeholder')
            ->assertSee('select-content')
            ->assertSee('select-help')
            ->assertSee('select-clearable')
            ->assertSee('select-sections')
            ->assertSee('app-ui:select:change');
    }
}
